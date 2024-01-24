<?php

namespace App\Controller;

use App\Entity\TelephoneFixe;
use App\Form\TelephoneFixeFormType;
use App\Repository\TelephoneFixeRepository;
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

#[Route('/admin/telephones/fixes', name: 'admin.telephone_fixe.')]
class TelephoneFixeController extends AbstractController
{
    private $repository;
    private $menu_active = "telephone_fixe";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $telephones = $registry->getManager()->getRepository(TelephoneFixe::class)->createQueryBuilder('t')
            ->select('t.id, t.ligne, t.marque, t.modele, fournisseur.nom as fournisseur_nom, entreprise.nom as entreprise_nom, etat.nom as etat_nom, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, emplacement.nom as emplacement_nom, t.numero_serie, t.ip, t.type, t.date_achat, t.date_garantie, t.date_installation, t.commentaire')
            ->leftJoin('t.utilisateur', 'utilisateur')
            ->leftJoin('t.etat', 'etat')
            ->leftJoin('t.fournisseur', 'fournisseur')
            ->leftJoin('t.entreprise', 'entreprise')
            ->leftJoin('t.emplacement', 'emplacement')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('telephone_fixe/show.html.twig', [
            'telephones' => $telephones,
            'menu_active' => $this->menu_active,
        ]);
    }

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

            // Permet de télécharger le fichier
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

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $telephone = new TelephoneFixe();

        $telephoneForm = $this->createForm(TelephoneFixeFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone fixe a bien été ajouté !");

            return $this->redirectToRoute('admin.telephone_fixe.show');
        }

        return $this->render('telephone_fixe/add.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(TelephoneFixe $telephone, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        $telephoneForm = $this->createForm(TelephoneFixeFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $notificationService->deleteNotification("telephone_fixe", $telephone->getId());

            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone fixe a bien été modifié !");

            return $this->redirectToRoute('admin.telephone_fixe.show');
        }

        return $this->render('telephone_fixe/edit.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(TelephoneFixe $telephone, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$telephone->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("telephone_fixe", $telephone->getId());

            $entityManager->remove($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone fixe a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.telephone_fixe.show');
    }
}
