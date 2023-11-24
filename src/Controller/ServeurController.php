<?php

namespace App\Controller;

use App\Entity\Serveur;
use App\Form\ServeurFormType;
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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/admin/serveurs', name: 'admin.serveur.')]
class ServeurController extends AbstractController
{
    private $repository;
    private $menu_active = "serveur";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $serveurs = $registry->getManager()->getRepository(Serveur::class)->createQueryBuilder('s')
            ->select('s.id, s.nom, s.marque, s.modele, emplacement.nom as emplacement_nom, etat.nom as etat_nom, entreprise.nom as entreprise_nom, fournisseur.nom as fournisseur_nom, s.numero_serie, s.ip, s.processeur, s.memoire, s.stockage_nombre, stockage.nom as stockage_nom, s.stockage_type, systeme_exploitation.nom as systeme_exploitation_nom, s.physique, s.date_contrat, s.date_achat, s.date_garantie, s.commentaire')
            ->leftJoin('s.stockage', 'stockage')
            ->leftJoin('s.emplacement', 'emplacement')
            ->leftJoin('s.etat', 'etat')
            ->leftJoin('s.systeme_exploitation', 'systeme_exploitation')
            ->leftJoin('s.entreprise', 'entreprise')
            ->leftJoin('s.fournisseur', 'fournisseur')
            ->orderBy('s.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('serveur/show.html.twig', [
            'serveurs' => $serveurs,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $serveur = new Serveur();

        $serveurForm = $this->createForm(ServeurFormType::class, $serveur);

        $serveurForm->handleRequest($request);

        if($serveurForm->isSubmitted() && $serveurForm->isValid())
        {
            $entityManager->persist($serveur);
            $entityManager->flush();

            $this->addFlash('success', "Le serveur a bien été ajouté !");

            return $this->redirectToRoute('admin.serveur.show');
        }

        return $this->render('serveur/add.html.twig', [
            'serveurForm' => $serveurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Serveur $serveur, EntityManagerInterface $entityManager, Request $request, UrlGeneratorInterface $urlGenerator): Response
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
            $entityManager->persist($serveur);
            $entityManager->flush();

            $this->addFlash('success', "Le serveur a bien été modifié !");

            return $this->redirectToRoute('admin.serveur.show');
        }

        return $this->render('serveur/edit.html.twig', [
            'serveurForm' => $serveurForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Serveur $serveur, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$serveur->getId(), $request->get('_token')))
        {
            $entityManager->remove($serveur);
            $entityManager->flush();

            $this->addFlash('success', "Le serveur a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.serveur.show');
    }
}
