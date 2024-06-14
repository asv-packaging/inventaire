<?php

namespace App\Controller;

use App\Entity\Ecran;
use App\Entity\Notification;
use App\Form\EcranFormType;
use App\Repository\EcranRepository;
use App\Service\ExcelExportService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[Route('/gestion/ecrans', name: 'admin.ecran.')]
class EcranController extends AbstractController
{
    private $menu_active = "ecran";

    /**
     * @param EcranRepository $ecranRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des écrans
     */
    #[Route(name: 'show')]
    public function index(EcranRepository $ecranRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $recherche = $request->query->get('par');
        $search = $request->query->get('q');

        if ($recherche && $search)
        {
            $ecransRepo = $ecranRepository->findByCritere($recherche, $search);
        }
        else
        {
            $ecransRepo = $ecranRepository->findBy([], ['id' => 'DESC']);
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $ecrans = $paginator->paginate(
            $ecransRepo,
            $page,
            $limit
        );

        $total = $ecrans->getTotalItemCount();

        return $this->render('ecran/show.html.twig', [
            'ecrans' => $ecrans,
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param ExcelExportService $excelExportService
     * @param EcranRepository $repository
     * @return Response
     * Permet d'exporter les données des écrans dans un fichier Excel
     */
    #[Route('/exporter', name: 'export')]
    public function exportDataToExcel(ExcelExportService $excelExportService, EcranRepository $repository): Response
    {
        $ecrans = $repository->findAll();

        if(count($ecrans) !== 0)
        {
            $headers = ['ID', 'Nom', 'Marque', 'Modèle', 'Utilisateur', 'Emplacement', 'Site', 'Fournisseur', 'Numéro de série', 'État', 'Date d\'installation', 'Date d\'achat', 'Date de garantie', 'Commentaire'];
            $data = [];

            foreach($ecrans as $ecran)
            {
                $data[] = [
                    $ecran->getId(),
                    $ecran->getNom(),
                    $ecran->getMarque() ?? 'N/A',
                    $ecran->getModele() ?? 'N/A',
                    $ecran->getUtilisateur() !== null ? $ecran->getUtilisateur()->getNom().' '.$ecran->getUtilisateur()->getPrenom() : 'N/A',
                    $ecran->getEmplacement()->getNom(),
                    $ecran->getEntreprise() !== null ? $ecran->getEntreprise()->getNom() : 'N/A',
                    $ecran->getFournisseur() !== null ? $ecran->getFournisseur()->getNom() : 'N/A',
                    $ecran->getNumeroSerie() ?? 'N/A',
                    $ecran->getEtat()->getNom(),
                    $ecran->getDateInstallation() ?? 'N/A',
                    $ecran->getDateAchat() ?? 'N/A',
                    $ecran->getDateGarantie() ?? 'N/A',
                    $ecran->getCommentaire() ?? 'N/A',
                ];
            }

            // Chemin où sauvegarder le fichier Excel
            $filePath = $this->getParameter('kernel.project_dir') . '/var/export_data_inventaire_ecrans.xlsx';

            // Utilise le service pour exporter les données
            $excelExportService->exportToExcel($headers, $data, $filePath);

            // Télécharge le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inventaire_ecrans.xlsx');
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
            $this->addFlash('danger', "Vous ne pouvez pas exporter les données car aucun écran n'a été trouvé.");

            return $this->redirectToRoute('admin.ecran.show');
        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de créer un écran
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $ecran = new Ecran();

        $ecranForm = $this->createForm(EcranFormType::class, $ecran);

        $ecranForm->handleRequest($request);

        if($ecranForm->isSubmitted() && $ecranForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $ecranForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $ecran->setSlug($slug);

            $entityManager->persist($ecran);
            $entityManager->flush();

            $this->addFlash('success', "L'écran a été créé.");

            return $this->redirectToRoute('admin.ecran.show');
        }

        return $this->render('ecran/create.html.twig', [
            'ecranForm' => $ecranForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param int $id
     * @param EcranRepository $ecranRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     * Permet de rediriger vers l'écran en fonction de son ID
     */
    #[Route('/{id}', name: 'redirectToSlug', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function redirectToSlug(int $id, EcranRepository $ecranRepository, UrlGeneratorInterface $urlGenerator): Response
    {
        $ecran = $ecranRepository->find($id);
        if (!$ecran) {
            throw $this->createNotFoundException();
        }

        $url = $urlGenerator->generate('admin.ecran.edit', ['slug' => $ecran->getSlug()]);
        return $this->redirect($url);
    }

    /**
     * @param Ecran $ecran
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @param NotificationService $notificationService
     * @return Response
     * Permet de modifier un écran
     */
    #[Route('/{slug}', name: 'edit', methods: ['GET', 'POST'], requirements: ['slug' => '[a-z0-9-]+'])]
    public function edit(Ecran $ecran, EntityManagerInterface $entityManager, Request $request, UrlGeneratorInterface $urlGenerator, NotificationService $notificationService): Response
    {
        $currentUrl = $urlGenerator->generate('admin.ecran.redirectToSlug', ['id' => $ecran->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

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
            $label->setText($ecran->getNom())
        )->getDataUri();

        $ecranForm = $this->createForm(EcranFormType::class, $ecran);

        $ecranForm->handleRequest($request);

        if($ecranForm->isSubmitted() && $ecranForm->isValid())
        {

            $slugger = new AsciiSlugger();
            $nom = $ecranForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $ecran->setSlug($slug);

            $notificationService->deleteNotification("ecran", $ecran->getId());

            $entityManager->persist($ecran);
            $entityManager->flush();

            $this->addFlash('success', "L'écran a été modifié.");

            return $this->redirectToRoute('admin.ecran.show');
        }

        return $this->render('ecran/edit.html.twig', [
            'ecranForm' => $ecranForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    /**
     * @param Ecran $ecran
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param NotificationService $notificationService
     * @return Response
     * Permet de supprimer un écran
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Ecran $ecran, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$ecran->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("ecran", $ecran->getId());

            $entityManager->remove($ecran);
            $entityManager->flush();

            $this->addFlash('success', "L'écran a été supprimé.");
        }

        return $this->redirectToRoute('admin.ecran.show');
    }
}
