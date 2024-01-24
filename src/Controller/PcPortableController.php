<?php

namespace App\Controller;

use App\Entity\PcPortable;
use App\Form\PcPortableFormType;
use App\Repository\PcPortableRepository;
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

#[Route('/admin/pc/portables', name: 'admin.pc_portable.')]
class PcPortableController extends AbstractController
{
    private $repository;
    private $menu_active = "pc_portable";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $pcPortables = $registry->getManager()->getRepository(PcPortable::class)->createQueryBuilder('pc_portable')
            ->select('pc_portable.id, pc_portable.nom, pc_portable.marque, pc_portable.modele, emplacement.nom as emplacement_nom, etat.nom as etat_nom, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, systeme_exploitation.nom as systeme_exploitation_nom, fournisseur.nom as fournisseur_nom, entreprise.nom as entreprise_nom, pc_portable.numero_serie, pc_portable.ip, pc_portable.processeur, pc_portable.memoire, pc_portable.stockage_nombre, stockage.nom as stockage_nom, pc_portable.stockage_type, pc_portable.date_installation, pc_portable.date_achat, pc_portable.date_garantie, pc_portable.commentaire')
            ->leftJoin('pc_portable.utilisateur', 'utilisateur')
            ->leftJoin('pc_portable.emplacement', 'emplacement')
            ->leftJoin('pc_portable.etat', 'etat')
            ->leftJoin('pc_portable.stockage', 'stockage')
            ->leftJoin('pc_portable.systeme_exploitation', 'systeme_exploitation')
            ->leftJoin('pc_portable.fournisseur', 'fournisseur')
            ->leftJoin('pc_portable.entreprise', 'entreprise')
            ->orderBy('pc_portable.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('pc_portable/show.html.twig', [
            'pcPortables' => $pcPortables,
            'menu_active' => $this->menu_active,
        ]);
    }

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
                    $pcPortable->getFournisseur()->getNom(),
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

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $pcPortable = new PcPortable();

        $pcPortableForm = $this->createForm(PcPortableFormType::class, $pcPortable);

        $pcPortableForm->handleRequest($request);

        if($pcPortableForm->isSubmitted() && $pcPortableForm->isValid())
        {
            $entityManager->persist($pcPortable);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Portable a bien été ajouté !");

            return $this->redirectToRoute('admin.pc_portable.show');
        }

        return $this->render('pc_portable/add.html.twig', [
            'pcPortableForm' => $pcPortableForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

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

            $this->addFlash('success', "Le PC Portable a bien été modifié !");

            return $this->redirectToRoute('admin.pc_portable.show');
        }

        return $this->render('pc_portable/edit.html.twig', [
            'pcPortableForm' => $pcPortableForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(PcPortable $pcPortable, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$pcPortable->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("pc_portable", $pcPortable->getId());

            $entityManager->remove($pcPortable);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Portable a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.pc_portable.show');
    }
}
