<?php

namespace App\Controller;

use App\Entity\PcPortable;
use App\Form\PcPortableFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/pc/portables', name: 'admin.pc_portable.')]
class PcPortableController extends AbstractController
{
    private $repository;
    private $menu_active = "pc_portable";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $pcPortables = $registry->getManager()->getRepository(PcPortable::class)->createQueryBuilder('pc_portable')
            ->select('pc_portable.id, pc_portable.nom, pc_portable.marque, pc_portable.modele, emplacement.nom as emplacement_nom, etat.nom as etat_nom, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, pc_portable.numero_serie, pc_portable.ip, pc_portable.processeur, pc_portable.memoire, pc_portable.stockage_nombre, stockage.nom as stockage_nom, pc_portable.os, pc_portable.date_achat, pc_portable.date_garantie, pc_portable.commentaire')
            ->leftJoin('pc_portable.utilisateur', 'utilisateur')
            ->leftJoin('pc_portable.emplacement', 'emplacement')
            ->leftJoin('pc_portable.etat', 'etat')
            ->leftJoin('pc_portable.stockage', 'stockage')
            ->orderBy('pc_portable.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('pc_portable/show.html.twig', [
            'pcPortables' => $pcPortables,
            'menu_active' => $this->menu_active,
        ]);
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
    public function edit(PcPortable $pcPortable, EntityManagerInterface $entityManager, Request $request): Response
    {
        $pcPortableForm = $this->createForm(PcPortableFormType::class, $pcPortable);

        $pcPortableForm->handleRequest($request);

        if($pcPortableForm->isSubmitted() && $pcPortableForm->isValid())
        {
            $entityManager->persist($pcPortable);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Portable a bien été modifié !");

            return $this->redirectToRoute('admin.pc_portable.show');
        }

        return $this->render('pc_portable/edit.html.twig', [
            'pcPortableForm' => $pcPortableForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(PcPortable $pcPortable, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$pcPortable->getId(), $request->get('_token')))
        {
            $entityManager->remove($pcPortable);
            $entityManager->flush();

            $this->addFlash('success', "Le PC Portable a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.pc_portable.show');
    }
}
