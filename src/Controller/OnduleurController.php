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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/gestion/onduleurs', name: 'admin.onduleur.')]
class OnduleurController extends AbstractController
{
    private $menu_active = "onduleur";

    /**
     * @param OnduleurRepository $onduleurRepository
     * @return Response
     * Permet d'afficher la liste des onduleurs
     */
    #[Route('', name: 'show')]
    public function index(OnduleurRepository $onduleurRepository): Response
    {
        $onduleurs = $onduleurRepository->findBy([], ['id' => 'DESC']);

        return $this->render('onduleur/show.html.twig', [
            'onduleurs' => $onduleurs,
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
            $headers = ['ID', 'Nom', 'Marque', 'Modèle', 'Numéro de série', 'Capacité', 'Type de prise', 'Emplacement', 'Site', 'Fournisseur', 'État', 'Date d\'installation', 'Date d\'achat', 'Date de garantie', 'Commentaire'];
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
     * Permet d'ajouter un onduleur
     */
    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $onduleur = new Onduleur();

        $onduleurForm = $this->createForm(OnduleurFormType::class, $onduleur);

        $onduleurForm->handleRequest($request);

        if($onduleurForm->isSubmitted() && $onduleurForm->isValid())
        {
            $entityManager->persist($onduleur);
            $entityManager->flush();

            $this->addFlash('success', "L'onduleur a bien été ajouté !");

            return $this->redirectToRoute('admin.onduleur.show');
        }

        return $this->render('onduleur/add.html.twig', [
            'onduleurForm' => $onduleurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
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
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Onduleur $onduleur, EntityManagerInterface $entityManager, Request $request, UrlGeneratorInterface $urlGenerator, NotificationService $notificationService): Response
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
            $label->setText($onduleur->getNom())
        )->getDataUri();

        $onduleurForm = $this->createForm(OnduleurFormType::class, $onduleur);

        $onduleurForm->handleRequest($request);

        if($onduleurForm->isSubmitted() && $onduleurForm->isValid())
        {
            $notificationService->deleteNotification("onduleur", $onduleur->getId());

            $entityManager->persist($onduleur);
            $entityManager->flush();

            $this->addFlash('success', "L'onduleur a bien été modifié !");

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

            $this->addFlash('success', "L'onduleur a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.onduleur.show');
    }
}
