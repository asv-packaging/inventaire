<?php

namespace App\Controller;

use App\Entity\PcFixe;
use App\Form\PcFixeFormType;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/admin/pc/fixes', name: 'admin.pc_fixe.')]
class PcFixeController extends AbstractController
{
    private $repository;
    private $menu_active = "pc_fixe";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $pcFixes = $registry->getManager()->getRepository(PcFixe::class)->createQueryBuilder('pc_fixe')
            ->select('pc_fixe.id, pc_fixe.nom, pc_fixe.marque, pc_fixe.modele, emplacement.nom as emplacement_nom, etat.nom as etat_nom, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, systeme_exploitation.nom as systeme_exploitation_nom, fournisseur.nom as fournisseur_nom, entreprise.nom as entreprise_nom, pc_fixe.numero_serie, pc_fixe.ip, pc_fixe.processeur, pc_fixe.memoire, pc_fixe.stockage_nombre, stockage.nom as stockage_nom, pc_fixe.stockage_type, pc_fixe.date_installation, pc_fixe.date_achat, pc_fixe.date_garantie, pc_fixe.commentaire')
            ->leftJoin('pc_fixe.utilisateur', 'utilisateur')
            ->leftJoin('pc_fixe.emplacement', 'emplacement')
            ->leftJoin('pc_fixe.etat', 'etat')
            ->leftJoin('pc_fixe.stockage', 'stockage')
            ->leftJoin('pc_fixe.systeme_exploitation', 'systeme_exploitation')
            ->leftJoin('pc_fixe.fournisseur', 'fournisseur')
            ->leftJoin('pc_fixe.entreprise', 'entreprise')
            ->orderBy('pc_fixe.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('pc_fixe/show.html.twig', [
            'pcFixes' => $pcFixes,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $pcFixe = new PcFixe();

        $pcFixeForm = $this->createForm(PcFixeFormType::class, $pcFixe);

        $pcFixeForm->handleRequest($request);

        if($pcFixeForm->isSubmitted() && $pcFixeForm->isValid())
        {
            $entityManager->persist($pcFixe);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Fixe a bien été ajouté !");

            return $this->redirectToRoute('admin.pc_fixe.show');
        }

        return $this->render('pc_fixe/add.html.twig', [
            'pcFixeForm' => $pcFixeForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

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

            $this->addFlash('success', "Le PC Fixe a bien été modifié !");

            return $this->redirectToRoute('admin.pc_fixe.show');
        }

        return $this->render('pc_fixe/edit.html.twig', [
            'pcFixeForm' => $pcFixeForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(PcFixe $pcFixe, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$pcFixe->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("pc_fixe", $pcFixe->getId());

            $entityManager->remove($pcFixe);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Fixe a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.pc_fixe.show');
    }
}
