<?php

namespace App\Controller;

use App\Entity\Tablette;
use App\Form\TabletteFormType;
use App\Repository\TabletteRepository;
use App\Service\ExcelExportService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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

#[Route('/admin/tablettes', name: 'admin.tablette.')]
class TabletteController extends AbstractController
{
    private $repository;
    private $menu_active = "tablette";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $tablettes = $registry->getManager()->getRepository(Tablette::class)->createQueryBuilder('t')
            ->select('t.id, t.nom, t.marque, t.modele, fournisseur.nom as fournisseur_nom, emplacement.nom as emplacement_nom, entreprise.nom as entreprise_nom, etat.nom as etat_nom, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, t.numero_serie, t.ip, t.date_achat, t.date_garantie, t.commentaire')
            ->leftJoin('t.utilisateur', 'utilisateur')
            ->leftJoin('t.emplacement', 'emplacement')
            ->leftJoin('t.fournisseur', 'fournisseur')
            ->leftJoin('t.entreprise', 'entreprise')
            ->leftJoin('t.etat', 'etat')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('tablette/show.html.twig', [
            'tablettes' => $tablettes,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/exporter', name: 'export')]
    public function exportDataToExcel(ExcelExportService $excelExportService, TabletteRepository $repository): Response
    {
        $tablettes = $repository->findAll();

        if(count($tablettes) !== 0)
        {
            $headers = ['ID', 'Nom', 'IP', 'Marque', 'Modèle', 'Utilisateur', 'Numéro de série', 'Emplacement', 'Site', 'Fournisseur', 'État', 'Date d\'installation', 'Date d\'achat', 'Date de garantie', 'Commentaire'];
            $data = [];

            foreach($tablettes as $tablette)
            {
                $data[] = [
                    $tablette->getId(),
                    $tablette->getNom(),
                    $tablette->getIp() ?? 'N/A',
                    $tablette->getMarque() ?? 'N/A',
                    $tablette->getModele() ?? 'N/A',
                    $tablette->getUtilisateur() !== null ? $tablette->getUtilisateur()->getNom() . ' ' . $tablette->getUtilisateur()->getPrenom() : 'N/A',
                    $tablette->getNumeroSerie() ?? 'N/A',
                    $tablette->getEmplacement()->getNom(),
                    $tablette->getEntreprise() !== null ? $tablette->getEntreprise()->getNom() : 'N/A',
                    $tablette->getFournisseur() !== null ? $tablette->getFournisseur()->getNom() : 'N/A',
                    $tablette->getEtat()->getNom(),
                    $tablette->getDateInstallation() ?? 'N/A',
                    $tablette->getDateAchat() ?? 'N/A',
                    $tablette->getDateGarantie() ?? 'N/A',
                    $tablette->getCommentaire() ?? 'N/A',
                ];
            }

            // Chemin où sauvegarder le fichier Excel
            $filePath = $this->getParameter('kernel.project_dir') . '/var/export_data_inventaire_tablettes.xlsx';

            // Utilise le service pour exporter les données
            $excelExportService->exportToExcel($headers, $data, $filePath);

            // Permet de télécharger le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inventaire_tablettes.xlsx');
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
            $this->addFlash('danger', "Vous ne pouvez pas exporter les données car aucune tablette n'a été trouvé !");

            return $this->redirectToRoute('admin.tablette.show');
        }
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $tablette = new Tablette();

        $tabletteForm = $this->createForm(TabletteFormType::class, $tablette);

        $tabletteForm->handleRequest($request);

        if($tabletteForm->isSubmitted() && $tabletteForm->isValid())
        {
            $entityManager->persist($tablette);
            $entityManager->flush();

            $this->addFlash('success', "La tablette a bien été ajoutée !");

            return $this->redirectToRoute('admin.tablette.show');
        }

        return $this->render('tablette/add.html.twig', [
            'tabletteForm' => $tabletteForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Tablette $tablette, EntityManagerInterface $entityManager, Request $request, UrlGeneratorInterface $urlGenerator, NotificationService $notificationService): Response
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
            $label->setText($tablette->getNom())
        )->getDataUri();

        $tabletteForm = $this->createForm(TabletteFormType::class, $tablette);

        $tabletteForm->handleRequest($request);

        if($tabletteForm->isSubmitted() && $tabletteForm->isValid())
        {
            $notificationService->deleteNotification("tablette", $tablette->getId());

            $entityManager->persist($tablette);
            $entityManager->flush();

            $this->addFlash('success', "La tablette a bien été modifiée !");

            return $this->redirectToRoute('admin.tablette.show');
        }

        return $this->render('tablette/edit.html.twig', [
            'tabletteForm' => $tabletteForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Tablette $tablette, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$tablette->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("tablette", $tablette->getId());

            $entityManager->remove($tablette);
            $entityManager->flush();

            $this->addFlash('success', "La tablette a bien été supprimée !");
        }

        return $this->redirectToRoute('admin.tablette.show');
    }
}
