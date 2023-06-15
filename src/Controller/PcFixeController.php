<?php

namespace App\Controller;

use App\Entity\PcFixe;
use App\Form\PcFixeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/pc/fixes', name: 'admin.pc_fixe.')]
class PcFixeController extends AbstractController
{
    private $repository;
    private $menu_active = "pc_fixe";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $pcFixes = $registry->getManager()->getRepository(PcFixe::class)->createQueryBuilder('pc_fixe')
            ->select('pc_fixe.id, pc_fixe.nom, pc_fixe.marque, pc_fixe.modele, emplacement.nom as emplacement_nom, etat.nom as etat_nom, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, pc_fixe.numero_serie, pc_fixe.ip, pc_fixe.processeur, pc_fixe.memoire, pc_fixe.stockage_nombre, stockage.nom as stockage_nom, pc_fixe.os, pc_fixe.date_achat, pc_fixe.date_garantie, pc_fixe.commentaire')
            ->leftJoin('pc_fixe.utilisateur', 'utilisateur')
            ->leftJoin('pc_fixe.emplacement', 'emplacement')
            ->leftJoin('pc_fixe.etat', 'etat')
            ->leftJoin('pc_fixe.stockage', 'stockage')
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
    public function edit(PcFixe $pcFixe, EntityManagerInterface $entityManager, Request $request): Response
    {
        $pcFixeForm = $this->createForm(PcFixeFormType::class, $pcFixe);

        $pcFixeForm->handleRequest($request);

        if($pcFixeForm->isSubmitted() && $pcFixeForm->isValid())
        {
            $entityManager->persist($pcFixe);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Fixe a bien été modifié !");

            return $this->redirectToRoute('admin.pc_fixe.show');
        }

        return $this->render('pc_fixe/edit.html.twig', [
            'pcFixeForm' => $pcFixeForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(PcFixe $pcFixe, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$pcFixe->getId(), $request->get('_token')))
        {
            $entityManager->remove($pcFixe);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Fixe a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.pc_fixe.show');
    }
}
