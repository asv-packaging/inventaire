<?php

namespace App\Controller;

use App\Entity\TelephoneFixe;
use App\Form\TelephoneFixeFormType;
use App\Repository\TelephoneFixeRepository;
use App\Service\ExcelExportService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/gestion/telephones/fixes', name: 'admin.telephone_fixe.')]
class TelephoneFixeController extends AbstractController
{
    private $menu_active = "telephone_fixe";

    /**
     * @param TelephoneFixeRepository $telephoneFixeRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des téléphones fixes
     */
    #[Route(name: 'show')]
    public function index(TelephoneFixeRepository $telephoneFixeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $recherche = $request->query->get('par');
        $search = $request->query->get('q');

        if ($recherche && $search)
        {
            $telephonesRepo = $telephoneFixeRepository->findByCritere($recherche, $search);
        }
        else
        {
            $telephonesRepo = $telephoneFixeRepository->findBy([], ['id' => 'DESC']);
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $telephones = $paginator->paginate(
            $telephonesRepo,
            $page,
            $limit
        );

        $total = $telephones->getTotalItemCount();

        return $this->render('telephone_fixe/show.html.twig', [
            'telephones' => $telephones,
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param ExcelExportService $excelExportService
     * @param TelephoneFixeRepository $repository
     * @return Response
     * Permet d'exporter les données des téléphones fixes au format Excel
     */
    #[Route('/exporter', name: 'export')]
    public function exportDataToExcel(ExcelExportService $excelExportService, TelephoneFixeRepository $repository): Response
    {
        $telephones = $repository->findAll();

        if(count($telephones) !== 0)
        {
            $headers = ['ID', 'Ligne', 'Marque', 'Modèle', 'Type', 'IP', 'Utilisateur', 'Numéro de série', 'Emplacement', 'Site', 'Fournisseur', 'État', 'Date d\'installation', 'Date d\'achat', 'Date de garantie', 'Commentaire'];
            $data = [];

            foreach($telephones as $telephone)
            {
                $data[] = [
                    $telephone->getId(),
                    $telephone->getLigne(),
                    $telephone->getMarque() ?? 'N/A',
                    $telephone->getModele() ?? 'N/A',
                    $telephone->getType() ?? 'N/A',
                    $telephone->getIp() ?? 'N/A',
                    $telephone->getUtilisateur() !== null ? $telephone->getUtilisateur()->getNom() . ' ' . $telephone->getUtilisateur()->getPrenom() : 'N/A',
                    $telephone->getNumeroSerie() ?? 'N/A',
                    $telephone->getEmplacement()->getNom(),
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
            $filePath = $this->getParameter('kernel.project_dir') . '/var/export_data_inventaire_telephonesFixes.xlsx';

            // Utilise le service pour exporter les données
            $excelExportService->exportToExcel($headers, $data, $filePath);

            // Télécharge le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inventaire_telephonesFixes.xlsx');
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
            $this->addFlash('danger', "Vous ne pouvez pas exporter les données car aucun téléphone fixe n'a été trouvé !");

            return $this->redirectToRoute('admin.telephone_fixe.show');
        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de créer un téléphone fixe
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $telephone = new TelephoneFixe();

        $telephoneForm = $this->createForm(TelephoneFixeFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $telephoneForm->get('ligne')->getData();
            $slug = strtolower($slugger->slug($nom));

            $telephone->setSlug($slug);

            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone fixe a été créé.");

            return $this->redirectToRoute('admin.telephone_fixe.show');
        }

        return $this->render('telephone_fixe/create.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param TelephoneFixe $telephone
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param NotificationService $notificationService
     * @return Response
     * Permet de modifier un téléphone fixe
     */
    #[Route('/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(#[MapEntity(mapping: ['slug' => 'slug'])] TelephoneFixe $telephone, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        $telephoneForm = $this->createForm(TelephoneFixeFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $telephoneForm->get('ligne')->getData();
            $slug = strtolower($slugger->slug($nom));

            $telephone->setSlug($slug);

            $notificationService->deleteNotification("telephone_fixe", $telephone->getId());

            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone fixe a été modifié.");

            return $this->redirectToRoute('admin.telephone_fixe.show');
        }

        return $this->render('telephone_fixe/edit.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param TelephoneFixe $telephone
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param NotificationService $notificationService
     * @return Response
     * Permet de supprimer un téléphone fixe
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(TelephoneFixe $telephone, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$telephone->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("telephone_fixe", $telephone->getId());

            $entityManager->remove($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone fixe a été supprimé.");
        }

        return $this->redirectToRoute('admin.telephone_fixe.show');
    }
}
