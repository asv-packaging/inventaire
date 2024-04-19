<?php

namespace App\Controller;

use App\Entity\PcPortable;
use App\Form\PcPortableFormType;
use App\Repository\PcPortableRepository;
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

#[Route('/gestion/pc/portables', name: 'admin.pc_portable.')]
class PcPortableController extends AbstractController
{
    private $menu_active = "pc_portable";

    /**
     * @param PcPortableRepository $pcPortableRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des PC Portables
     */
    #[Route(name: 'show')]
    public function index(PcPortableRepository $pcPortableRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $recherche = $request->query->get('par');
        $search = $request->query->get('q');

        if ($recherche && $search)
        {
            $pcPortablesRepo = $pcPortableRepository->findByCritere($recherche, $search);
        }
        else
        {
            $pcPortablesRepo = $pcPortableRepository->findBy([], ['id' => 'DESC']);
        }

        $pcPortables = $paginator->paginate(
            $pcPortablesRepo,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pc_portable/show.html.twig', [
            'pcPortables' => $pcPortables,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param ExcelExportService $excelExportService
     * @param PcPortableRepository $repository
     * @return Response
     * Permet d'exporter les données des PC Portables au format Excel
     */
    #[Route('/exporter', name: 'export')]
    public function exportDataToExcel(ExcelExportService $excelExportService, PcPortableRepository $repository): Response
    {
        $pcPortables = $repository->findAll();

        if(count($pcPortables) !== 0)
        {
            $headers = ['ID', 'Nom', 'IP', 'Marque', 'Modèle', 'Utilisateur', 'Processeur', 'Mémoire', 'Stockage', 'Type de stockage', 'Système d\'exploitation', 'Numéro de série', 'Emplacement', 'Site', 'Fournisseur', 'État', 'Date d\'installation', 'Date d\'achat', 'Date de garantie', 'Commentaire'];
            $data = [];

            foreach($pcPortables as $pcPortable)
            {
                $data[] = [
                    $pcPortable->getId(),
                    $pcPortable->getNom(),
                    $pcPortable->getIp() ?? 'N/A',
                    $pcPortable->getMarque() ?? 'N/A',
                    $pcPortable->getModele() ?? 'N/A',
                    $pcPortable->getUtilisateur() !== null ? $pcPortable->getUtilisateur()->getNom().' '.$pcPortable->getUtilisateur()->getPrenom() : 'N/A',
                    $pcPortable->getProcesseur() ?? 'N/A',
                    $pcPortable->getMemoire().' Go',
                    $pcPortable->getStockage() !== null ? $pcPortable->getStockageNombre().' '.$pcPortable->getStockage()->getNom() : 'N/A',
                    $pcPortable->getStockageType() ?? 'N/A',
                    $pcPortable->getSystemeExploitation() !== null ? $pcPortable->getSystemeExploitation()->getNom() : 'N/A',
                    $pcPortable->getNumeroSerie() ?? 'N/A',
                    $pcPortable->getEmplacement()->getNom(),
                    $pcPortable->getEntreprise() !== null ? $pcPortable->getEntreprise()->getNom() : 'N/A',
                    $pcPortable->getFournisseur() !== null ? $pcPortable->getFournisseur()->getNom() : 'N/A',
                    $pcPortable->getEtat()->getNom(),
                    $pcPortable->getDateInstallation() ?? 'N/A',
                    $pcPortable->getDateAchat() ?? 'N/A',
                    $pcPortable->getDateGarantie() ?? 'N/A',
                    $pcPortable->getCommentaire() ?? 'N/A',
                ];
            }

            // Chemin où sauvegarder le fichier Excel
            $filePath = $this->getParameter('kernel.project_dir') . '/var/export_data_inventaire_pcPortables.xlsx';

            // Utilise le service pour exporter les données
            $excelExportService->exportToExcel($headers, $data, $filePath);

            // Permet de télécharger le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inventaire_pcPortables.xlsx');
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
            $this->addFlash('danger', "Vous ne pouvez pas exporter les données car aucun PC Portable n'a été trouvé !");

            return $this->redirectToRoute('admin.pc_portable.show');
        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de créer un PC Portable
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $pcPortable = new PcPortable();

        $pcPortableForm = $this->createForm(PcPortableFormType::class, $pcPortable);

        $pcPortableForm->handleRequest($request);

        if($pcPortableForm->isSubmitted() && $pcPortableForm->isValid())
        {
            $entityManager->persist($pcPortable);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Portable a été créé.");

            return $this->redirectToRoute('admin.pc_portable.show');
        }

        return $this->render('pc_portable/create.html.twig', [
            'pcPortableForm' => $pcPortableForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param PcPortable $pcPortable
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @param NotificationService $notificationService
     * @return Response
     * Permet de modifier un PC Portable
     */
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(PcPortable $pcPortable, EntityManagerInterface $entityManager, Request $request, UrlGeneratorInterface $urlGenerator, NotificationService $notificationService): Response
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
            $label->setText($pcPortable->getNom())
        )->getDataUri();

        $pcPortableForm = $this->createForm(PcPortableFormType::class, $pcPortable);

        $pcPortableForm->handleRequest($request);

        if($pcPortableForm->isSubmitted() && $pcPortableForm->isValid())
        {
            $notificationService->deleteNotification("pc_portable", $pcPortable->getId());

            $entityManager->persist($pcPortable);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Portable a été modifié.");

            return $this->redirectToRoute('admin.pc_portable.show');
        }

        return $this->render('pc_portable/edit.html.twig', [
            'pcPortableForm' => $pcPortableForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    /**
     * @param PcPortable $pcPortable
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param NotificationService $notificationService
     * @return Response
     * Permet de supprimer un PC Portable
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(PcPortable $pcPortable, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$pcPortable->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("pc_portable", $pcPortable->getId());

            $entityManager->remove($pcPortable);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Portable a été supprimé.");
        }

        return $this->redirectToRoute('admin.pc_portable.show');
    }
}
