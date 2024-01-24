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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/admin/ecrans', name: 'admin.ecran.')]
class EcranController extends AbstractController
{
    private $repository;
    private $menu_active = "ecran";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $ecrans = $registry->getManager()->getRepository(Ecran::class)->createQueryBuilder('e')
            ->select('e.id, e.nom, e.marque, e.modele, e.numero_serie, e.date_installation, e.date_achat, e.date_garantie, e.commentaire, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, emplacement.nom as emplacement_nom, etat.nom as etat_nom, fournisseur.nom as fournisseur_nom, entreprise.nom as entreprise_nom')
            ->leftJoin('e.utilisateur', 'utilisateur')
            ->leftJoin('e.emplacement', 'emplacement')
            ->leftJoin('e.fournisseur', 'fournisseur')
            ->leftJoin('e.entreprise', 'entreprise')
            ->leftJoin('e.etat', 'etat')
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('ecran/show.html.twig', [
            'ecrans' => $ecrans,
            'menu_active' => $this->menu_active,
        ]);
    }

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

            // Utiliser le service pour exporter les données
            $excelExportService->exportToExcel($headers, $data, $filePath);

            // Créer une réponse binaire pour télécharger le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inventaire_ecrans.xlsx');
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            // Supprimer le fichier après le téléchargement
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
            $this->addFlash('danger', "Vous ne pouvez pas exporter les données car aucun écran n'a été trouvé !");

            return $this->redirectToRoute('admin.ecran.show');
        }
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $ecran = new Ecran();

        $ecranForm = $this->createForm(EcranFormType::class, $ecran);

        $ecranForm->handleRequest($request);

        if($ecranForm->isSubmitted() && $ecranForm->isValid())
        {
            $entityManager->persist($ecran);
            $entityManager->flush();

            $this->addFlash('success', "L'écran a bien été ajouté !");

            return $this->redirectToRoute('admin.ecran.show');
        }

        return $this->render('ecran/add.html.twig', [
            'ecranForm' => $ecranForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Ecran $ecran, EntityManagerInterface $entityManager, Request $request, UrlGeneratorInterface $urlGenerator, NotificationService $notificationService): Response
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
            $label->setText($ecran->getNom())
        )->getDataUri();

        $ecranForm = $this->createForm(EcranFormType::class, $ecran);

        $ecranForm->handleRequest($request);

        if($ecranForm->isSubmitted() && $ecranForm->isValid())
        {
            $notificationService->deleteNotification("ecran", $ecran->getId());

            $entityManager->persist($ecran);
            $entityManager->flush();

            $this->addFlash('success', "L'écran a bien été modifié !");

            return $this->redirectToRoute('admin.ecran.show');
        }

        return $this->render('ecran/edit.html.twig', [
            'ecranForm' => $ecranForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Ecran $ecran, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$ecran->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("ecran", $ecran->getId());

            $entityManager->remove($ecran);
            $entityManager->flush();

            $this->addFlash('success', "L'écran a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.ecran.show');
    }
}
