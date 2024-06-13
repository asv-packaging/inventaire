<?php

namespace App\Controller;

use App\Entity\TelephonePortable;
use App\Form\TelephonePortableFormType;
use App\Repository\TelephonePortableRepository;
use App\Service\ExcelExportService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/gestion/telephones/portables', name: 'admin.telephone_portable.')]
class TelephonePortableController extends AbstractController
{
    private $menu_active = "telephone_portable";

    /**
     * @param TelephonePortableRepository $telephonePortableRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des téléphones portables
     */
    #[Route(name: 'show')]
    public function index(TelephonePortableRepository $telephonePortableRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $recherche = $request->query->get('par');
        $search = $request->query->get('q');

        if ($recherche && $search)
        {
            $telephonesRepo = $telephonePortableRepository->findByCritere($recherche, $search);
        }
        else
        {
            $telephonesRepo = $telephonePortableRepository->findBy([], ['id' => 'DESC']);
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $telephones = $paginator->paginate(
            $telephonesRepo,
            $page,
            $limit
        );

        $total = $telephones->getTotalItemCount();

        return $this->render('telephone_portable/show.html.twig', [
            'telephones' => $telephones,
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param ExcelExportService $excelExportService
     * @param TelephonePortableRepository $repository
     * @return Response
     * Permet d'exporter les données des téléphones portables au format Excel
     */
    #[Route('/exporter', name: 'export')]
    public function exportDataToExcel(ExcelExportService $excelExportService, TelephonePortableRepository $repository): Response
    {
        $telephones = $repository->findAll();

        if(count($telephones) !== 0)
        {
            $headers = ['ID', 'Ligne', 'Marque', 'Modèle', 'Utilisateur', 'IMEI 1', 'IMEI 2', 'Numéro de série', 'Site', 'Fournisseur', 'État', 'Date d\'installation', 'Date d\'achat', 'Date de garantie', 'Commentaire'];
            $data = [];

            foreach($telephones as $telephone)
            {
                $data[] = [
                    $telephone->getId(),
                    $telephone->getLigne(),
                    $telephone->getMarque() ?? 'N/A',
                    $telephone->getModele() ?? 'N/A',
                    $telephone->getUtilisateur() !== null ? $telephone->getUtilisateur()->getNom() . ' ' . $telephone->getUtilisateur()->getPrenom() : 'N/A',
                    $telephone->getImei1() ?? 'N/A',
                    $telephone->getImei2() ?? 'N/A',
                    $telephone->getNumeroSerie() ?? 'N/A',
                    $telephone->getEntreprise() !== null ? $telephone->getEntreprise()->getNom() : 'N/A',
                    $telephone->getFournisseur() !== null ? $telephone->getFournisseur()->getNom() : 'N/A',
                    $telephone->getEtat()->getNom(),
                    $telephone->getDateInstallation() ?? 'N/A',
                    $telephone->getDateAchat() ?? 'N/A',
                    $telephone->getDateGarantie() ?? 'N/A',
                    $telephone->getCommentaire() ?? 'N/A',
                ];
            }

            // Chemin où sauvegarder le fichier Excel
            $filePath = $this->getParameter('kernel.project_dir') . '/var/export_data_inventaire_telephonesPortables.xlsx';

            // Utilise le service pour exporter les données
            $excelExportService->exportToExcel($headers, $data, $filePath);

            // Télécharge le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inventaire_telephonesPortables.xlsx');
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            // Supprime le fichier après le téléchargement
            register_shutdown_function(function () use ($filePath)
            {
                if (file_exists($filePath))
                {
                    unlink($filePath);
                }
            });

            return $response;
        }
        else
        {
            $this->addFlash('danger', "Vous ne pouvez pas exporter les données car aucun téléphone portable n'a été trouvé !");

            return $this->redirectToRoute('admin.telephone_portable.show');
        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet d'ajouter un téléphone portable
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $telephone = new TelephonePortable();

        $telephoneForm = $this->createForm(TelephonePortableFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $telephoneForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $telephoneForm->setSlug($slug);

            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone portable a été créé.");

            return $this->redirectToRoute('admin.telephone_portable.show');
        }

        return $this->render('telephone_portable/create.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param TelephonePortable $telephone
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param NotificationService $notificationService
     * @return Response
     * Permet de modifier un téléphone portable
     */
    #[Route('/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(TelephonePortable $telephone, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        $telephoneForm = $this->createForm(TelephonePortableFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $telephoneForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $telephoneForm->setSlug($slug);

            $notificationService->deleteNotification("telephone_portable", $telephone->getId());

            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone portable a été modifié.");

            return $this->redirectToRoute('admin.telephone_portable.show');
        }

        return $this->render('telephone_portable/edit.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param TelephonePortable $telephone
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param NotificationService $notificationService
     * @return Response
     * Permet de supprimer un téléphone portable
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(TelephonePortable $telephone, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$telephone->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("telephone_portable", $telephone->getId());

            $entityManager->remove($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone portable a été supprimé.");
        }

        return $this->redirectToRoute('admin.telephone_portable.show');
    }
}
