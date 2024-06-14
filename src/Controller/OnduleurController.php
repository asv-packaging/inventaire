<?php

namespace App\Controller;

use App\Entity\Onduleur;
use App\Form\OnduleurFormType;
use App\Repository\OnduleurRepository;
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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/gestion/onduleurs', name: 'admin.onduleur.')]
class OnduleurController extends AbstractController
{
    private $menu_active = "onduleur";

    /**
     * @param OnduleurRepository $onduleurRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des onduleurs
     */
    #[Route(name: 'show')]
    public function index(OnduleurRepository $onduleurRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $recherche = $request->query->get('par');
        $search = $request->query->get('q');

        if ($recherche && $search)
        {
            $onduleursRepo = $onduleurRepository->findByCritere($recherche, $search);
        }
        else
        {
            $onduleursRepo = $onduleurRepository->findBy([], ['id' => 'DESC']);
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $onduleurs = $paginator->paginate(
            $onduleursRepo,
            $page,
            $limit
        );

        $total = $onduleurs->getTotalItemCount();

        return $this->render('onduleur/show.html.twig', [
            'onduleurs' => $onduleurs,
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param ExcelExportService $excelExportService
     * @param OnduleurRepository $repository
     * @return Response
     * Permet d'exporter les données des onduleurs au format Excel
     */
    #[Route('/exporter', name: 'export')]
    public function exportDataToExcel(ExcelExportService $excelExportService, OnduleurRepository $repository): Response
    {
        $onduleurs = $repository->findAll();

        if(count($onduleurs) !== 0)
        {
            $headers = ['ID', 'Nom', 'Marque', 'Modèle', 'Numéro de série', 'Capacité', 'Type de prise', 'Emplacement', 'Site', 'Fournisseur', 'État', 'Date d\'installation', 'Date d\'achat', 'Date de remplacement', 'Commentaire'];
            $data = [];

            foreach($onduleurs as $onduleur)
            {
                $data[] = [
                    $onduleur->getId(),
                    $onduleur->getNom(),
                    $onduleur->getMarque() ?? 'N/A',
                    $onduleur->getModele() ?? 'N/A',
                    $onduleur->getNumeroSerie() ?? 'N/A',
                    $onduleur->getCapacite().' Watts',
                    $onduleur->getTypePrise() ?? 'N/A',
                    $onduleur->getEmplacement()->getNom(),
                    $onduleur->getEntreprise() !== null ? $onduleur->getEntreprise()->getNom() : 'N/A',
                    $onduleur->getFournisseur()->getNom(),
                    $onduleur->getEtat()->getNom(),
                    $onduleur->getDateInstallation() ?? 'N/A',
                    $onduleur->getDateAchat() ?? 'N/A',
                    $onduleur->getDateGarantie() ?? 'N/A',
                    $onduleur->getCommentaire() ?? 'N/A',
                ];
            }

            // Chemin où sauvegarder le fichier Excel
            $filePath = $this->getParameter('kernel.project_dir') . '/var/export_data_inventaire_onduleurs.xlsx';

            // Utilise le service pour exporter les données
            $excelExportService->exportToExcel($headers, $data, $filePath);

            // Télécharge le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inventaire_onduleurs.xlsx');
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
            $this->addFlash('danger', "Vous ne pouvez pas exporter les données car aucun onduleur n'a été trouvé !");

            return $this->redirectToRoute('admin.onduleur.show');
        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de créer un onduleur
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $onduleur = new Onduleur();

        $onduleurForm = $this->createForm(OnduleurFormType::class, $onduleur);

        $onduleurForm->handleRequest($request);

        if($onduleurForm->isSubmitted() && $onduleurForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $onduleurForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $onduleur->setSlug($slug);

            $entityManager->persist($onduleur);
            $entityManager->flush();

            $this->addFlash('success', "L'onduleur a été créé.");

            return $this->redirectToRoute('admin.onduleur.show');
        }

        return $this->render('onduleur/create.html.twig', [
            'onduleurForm' => $onduleurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param int $id
     * @param OnduleurRepository $onduleurRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     * Permet de rediriger vers le slug de l'onduleur
     */
    #[Route('/{id}', name: 'redirectToSlug', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function redirectToSlug(int $id, OnduleurRepository $onduleurRepository, UrlGeneratorInterface $urlGenerator): Response
    {
        $onduleur = $onduleurRepository->find($id);
        if (!$onduleur) {
            throw $this->createNotFoundException();
        }

        $url = $urlGenerator->generate('admin.onduleur.edit', ['slug' => $onduleur->getSlug()]);
        return $this->redirect($url);
    }

    /**
     * @param Onduleur $onduleur
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @param NotificationService $notificationService
     * @return Response
     * Permet de modifier un onduleur
     */
    #[Route('/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Onduleur $onduleur, EntityManagerInterface $entityManager, Request $request, UrlGeneratorInterface $urlGenerator, NotificationService $notificationService): Response
    {
        $currentUrl = $urlGenerator->generate('admin.onduleur.edit', ['slug' => $onduleur->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);

        $writer = new PngWriter();
        $qrCode = QrCode::create($currentUrl)
            ->setEncoding(new Encoding('UTF-8'))
            ->setSize(150)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $label = Label::create('')->setFont(new NotoSans(12));

        $qrCodes = $writer->write(
            $qrCode,
            null,
            $label->setText($onduleur->getNom())
        )->getDataUri();

        $onduleurForm = $this->createForm(OnduleurFormType::class, $onduleur);

        $onduleurForm->handleRequest($request);

        if($onduleurForm->isSubmitted() && $onduleurForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $onduleurForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $onduleur->setSlug($slug);

            $notificationService->deleteNotification("onduleur", $onduleur->getId());

            $entityManager->persist($onduleur);
            $entityManager->flush();

            $this->addFlash('success', "L'onduleur a été modifié.");

            return $this->redirectToRoute('admin.onduleur.show');
        }

        return $this->render('onduleur/edit.html.twig', [
            'onduleurForm' => $onduleurForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    /**
     * @param Onduleur $onduleur
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param NotificationService $notificationService
     * @return Response
     * Permet de supprimer un onduleur
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Onduleur $onduleur, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$onduleur->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("onduleur", $onduleur->getId());
            $entityManager->remove($onduleur);
            $entityManager->flush();

            $this->addFlash('success', "L'onduleur a été supprimé.");
        }

        return $this->redirectToRoute('admin.onduleur.show');
    }
}
