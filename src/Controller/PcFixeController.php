<?php

namespace App\Controller;

use App\Entity\PcFixe;
use App\Form\PcFixeFormType;
use App\Repository\PcFixeRepository;
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
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/gestion/pc/fixes', name: 'admin.pc_fixe.')]
class PcFixeController extends AbstractController
{
    private $menu_active = "pc_fixe";

    /**
     * @param PcFixeRepository $pcFixeRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des PC Fixes
     */
    #[Route(name: 'show')]
    public function index(PcFixeRepository $pcFixeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pcFixesRepo = $pcFixeRepository->findBy([], ['id' => 'DESC']);

        $pcFixes = $paginator->paginate(
            $pcFixesRepo,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pc_fixe/show.html.twig', [
            'pcFixes' => $pcFixes,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param ExcelExportService $excelExportService
     * @param PcFixeRepository $repository
     * @return Response
     * Permet d'exporter les données des PC Fixes au format Excel
     */
    #[Route('/exporter', name: 'export')]
    public function exportDataToExcel(ExcelExportService $excelExportService, PcFixeRepository $repository): Response
    {
        $pcFixes = $repository->findAll();

        if(count($pcFixes) !== 0)
        {
            $headers = ['ID', 'Nom', 'IP', 'Marque', 'Modèle', 'Utilisateur', 'Processeur', 'Mémoire', 'Stockage', 'Type de stockage', 'Système d\'exploitation', 'Numéro de série', 'Emplacement', 'Site', 'Fournisseur', 'État', 'Date d\'installation', 'Date d\'achat', 'Date de garantie', 'Commentaire'];
            $data = [];

            foreach($pcFixes as $pcFixe)
            {
                $data[] = [
                    $pcFixe->getId(),
                    $pcFixe->getNom(),
                    $pcFixe->getIp() ?? 'N/A',
                    $pcFixe->getMarque() ?? 'N/A',
                    $pcFixe->getModele() ?? 'N/A',
                    $pcFixe->getUtilisateur() !== null ? $pcFixe->getUtilisateur()->getNom().' '.$pcFixe->getUtilisateur()->getPrenom() : 'N/A',
                    $pcFixe->getProcesseur() ?? 'N/A',
                    $pcFixe->getMemoire().' Go',
                    $pcFixe->getStockage() !== null ? $pcFixe->getStockageNombre().' '.$pcFixe->getStockage()->getNom() : 'N/A',
                    $pcFixe->getStockageType() ?? 'N/A',
                    $pcFixe->getSystemeExploitation() !== null ? $pcFixe->getSystemeExploitation()->getNom() : 'N/A',
                    $pcFixe->getNumeroSerie() ?? 'N/A',
                    $pcFixe->getEmplacement()->getNom(),
                    $pcFixe->getEntreprise() !== null ? $pcFixe->getEntreprise()->getNom() : 'N/A',
                    $pcFixe->getFournisseur() !== null ? $pcFixe->getFournisseur()->getNom() : 'N/A',
                    $pcFixe->getEtat()->getNom(),
                    $pcFixe->getDateInstallation() ?? 'N/A',
                    $pcFixe->getDateAchat() ?? 'N/A',
                    $pcFixe->getDateGarantie() ?? 'N/A',
                    $pcFixe->getCommentaire() ?? 'N/A',
                ];
            }

            // Chemin où sauvegarder le fichier Excel
            $filePath = $this->getParameter('kernel.project_dir') . '/var/export_data_inventaire_pcFixes.xlsx';

            // Utilise le service pour exporter les données
            $excelExportService->exportToExcel($headers, $data, $filePath);

            // Télécharge le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inventaire_pcFixes.xlsx');
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
            $this->addFlash('danger', "Vous ne pouvez pas exporter les données car aucun PC Fixe n'a été trouvé !");

            return $this->redirectToRoute('admin.pc_fixe.show');
        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de créer un PC Fixe
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $pcFixe = new PcFixe();

        $pcFixeForm = $this->createForm(PcFixeFormType::class, $pcFixe);

        $pcFixeForm->handleRequest($request);

        if($pcFixeForm->isSubmitted() && $pcFixeForm->isValid())
        {
            $entityManager->persist($pcFixe);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Fixe a été créé.");

            return $this->redirectToRoute('admin.pc_fixe.show');
        }

        return $this->render('pc_fixe/create.html.twig', [
            'pcFixeForm' => $pcFixeForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param PcFixe $pcFixe
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @param NotificationService $notificationService
     * @return Response
     * Permet de modifier un PC Fixe
     */
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(PcFixe $pcFixe, EntityManagerInterface $entityManager, Request $request, UrlGeneratorInterface $urlGenerator, NotificationService $notificationService): Response
    {
        $currentUrl = $urlGenerator->generate(
            $request->attributes->get('_route'),
            $request->attributes->get('_route_params'),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $writer = new PngWriter();
        $qrCode = QrCode::create($currentUrl)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(150)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $label = Label::create('')->setFont(new NotoSans(12));

        $qrCodes = $writer->write(
            $qrCode,
            null,
            $label->setText($pcFixe->getNom())
        )->getDataUri();

        $pcFixeForm = $this->createForm(PcFixeFormType::class, $pcFixe);

        $pcFixeForm->handleRequest($request);

        if($pcFixeForm->isSubmitted() && $pcFixeForm->isValid())
        {
            $notificationService->deleteNotification("pc_fixe", $pcFixe->getId());

            $entityManager->persist($pcFixe);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Fixe a été modifié.");

            return $this->redirectToRoute('admin.pc_fixe.show');
        }

        return $this->render('pc_fixe/edit.html.twig', [
            'pcFixeForm' => $pcFixeForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    /**
     * @param PcFixe $pcFixe
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param NotificationService $notificationService
     * @return Response
     * Permet de supprimer un PC Fixe
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(PcFixe $pcFixe, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$pcFixe->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("pc_fixe", $pcFixe->getId());

            $entityManager->remove($pcFixe);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Fixe a été supprimé.");
        }

        return $this->redirectToRoute('admin.pc_fixe.show');
    }
}
