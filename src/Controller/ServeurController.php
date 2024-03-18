<?php

namespace App\Controller;

use App\Entity\Serveur;
use App\Form\ServeurFormType;
use App\Repository\ServeurRepository;
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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/gestion/serveurs', name: 'admin.serveur.')]
class ServeurController extends AbstractController
{
    private $menu_active = "serveur";

    /**
     * @param ServeurRepository $serveurRepository
     * @return Response
     * Permet d'afficher la liste des serveurs
     */
    #[Route(name: 'show')]
    public function index(ServeurRepository $serveurRepository): Response
    {
        $serveurs = $serveurRepository->findBy([], ['id' => 'DESC']);

        return $this->render('serveur/show.html.twig', [
            'serveurs' => $serveurs,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param ExcelExportService $excelExportService
     * @param ServeurRepository $repository
     * @return Response
     * Permet d'exporter les données des serveurs au format Excel
     */
    #[Route('/exporter', name: 'export')]
    public function exportDataToExcel(ExcelExportService $excelExportService, ServeurRepository $repository): Response
    {
        $serveurs = $repository->findAll();

        if(count($serveurs) !== 0)
        {
            $headers = ['ID', 'Nom', 'IP', 'Marque', 'Modèle', 'Processeur', 'Mémoire', 'Stockage', 'Type de stockage', 'Système d\'exploitation', 'Type', 'Numéro de série', 'Emplacement', 'Site', 'Fournisseur', 'État', 'Date de contrat', 'Date d\'achat', 'Date de garantie', 'Commentaire'];
            $data = [];

            foreach($serveurs as $serveur)
            {
                $data[] = [
                    $serveur->getId(),
                    $serveur->getNom(),
                    $serveur->getIp() ?? 'N/A',
                    $serveur->getMarque() ?? 'N/A',
                    $serveur->getModele() ?? 'N/A',
                    $serveur->getProcesseur() ?? 'N/A',
                    $serveur->getMemoire().' Go',
                    $serveur->getStockage() !== null ? $serveur->getStockageNombre().' '.$serveur->getStockage()->getNom() : 'N/A',
                    $serveur->getStockageType() ?? 'N/A',
                    $serveur->getSystemeExploitation() !== null ? $serveur->getSystemeExploitation()->getNom() : 'N/A',
                    $serveur->isPhysique() ? 'Physique' : 'Virtuel',
                    $serveur->getNumeroSerie() ?? 'N/A',
                    $serveur->getEmplacement()->getNom(),
                    $serveur->getEntreprise() !== null ? $serveur->getEntreprise()->getNom() : 'N/A',
                    $serveur->getFournisseur()->getNom(),
                    $serveur->getEtat()->getNom(),
                    $serveur->getDateContrat() ?? 'N/A',
                    $serveur->getDateAchat() ?? 'N/A',
                    $serveur->getDateGarantie() ?? 'N/A',
                    $serveur->getCommentaire() ?? 'N/A',
                ];
            }

            // Chemin où sauvegarder le fichier Excel
            $filePath = $this->getParameter('kernel.project_dir') . '/var/export_data_inventaire_serveurs.xlsx';

            // Utilise le service pour exporter les données
            $excelExportService->exportToExcel($headers, $data, $filePath);

            // Télécharge le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inventaire_serveurs.xlsx');
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
            $this->addFlash('danger', "Vous ne pouvez pas exporter les données car aucun serveur n'a été trouvé !");

            return $this->redirectToRoute('admin.serveur.show');
        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de créer un serveur
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $serveur = new Serveur();

        $serveurForm = $this->createForm(ServeurFormType::class, $serveur);

        $serveurForm->handleRequest($request);

        if($serveurForm->isSubmitted() && $serveurForm->isValid())
        {
            $entityManager->persist($serveur);
            $entityManager->flush();

            $this->addFlash('success', "Le serveur a été créé.");

            return $this->redirectToRoute('admin.serveur.show');
        }

        return $this->render('serveur/create.html.twig', [
            'serveurForm' => $serveurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param Serveur $serveur
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @param NotificationService $notificationService
     * @return Response
     * Permet de modifier un serveur
     */
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Serveur $serveur, EntityManagerInterface $entityManager, Request $request, UrlGeneratorInterface $urlGenerator, NotificationService $notificationService): Response
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
            $label->setText($serveur->getNom())
        )->getDataUri();

        $serveurForm = $this->createForm(ServeurFormType::class, $serveur);

        $serveurForm->handleRequest($request);

        if($serveurForm->isSubmitted() && $serveurForm->isValid())
        {
            $notificationService->deleteNotification("serveur", $serveur->getId());

            $entityManager->persist($serveur);
            $entityManager->flush();

            $this->addFlash('success', "Le serveur a été modifié.");

            return $this->redirectToRoute('admin.serveur.show');
        }

        return $this->render('serveur/edit.html.twig', [
            'serveurForm' => $serveurForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    /**
     * @param Serveur $serveur
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param NotificationService $notificationService
     * @return Response
     * Permet de supprimer un serveur
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Serveur $serveur, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$serveur->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("serveur", $serveur->getId());

            $entityManager->remove($serveur);
            $entityManager->flush();

            $this->addFlash('success', "Le serveur a été supprimé.");
        }

        return $this->redirectToRoute('admin.serveur.show');
    }
}
