<?php

namespace App\Controller;

use App\Entity\Serveur;
use App\Form\ServeurFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/serveurs', name: 'admin.serveur.')]
class ServeurController extends AbstractController
{
    private $repository;
    private $menu_active = "serveur";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $serveurs = $registry->getManager()->getRepository(Serveur::class)->createQueryBuilder('s')
            ->select('s.id, s.nom, s.marque, s.modele, emplacement.nom as emplacement_nom, etat.nom as etat_nom, s.numero_serie, s.ip, s.processeur, s.memoire, s.stockage_nombre, stockage.nom as stockage_nom, s.os, s.physique, s.date_achat, s.date_garantie, s.commentaire')
            ->leftJoin('s.stockage', 'stockage')
            ->leftJoin('s.emplacement', 'emplacement')
            ->leftJoin('s.etat', 'etat')
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
    public function edit(Serveur $serveur, EntityManagerInterface $entityManager, Request $request): Response
    {
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
