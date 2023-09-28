<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurFormType;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/fournisseurs', name: 'admin.fournisseur.')]
class FournisseurController extends AbstractController
{
    private $repository;
    private $menu_active = "fournisseur";

    public function __construct(FournisseurRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('', name: 'show')]
    public function index(): Response
    {
        $fournisseurs = $this->repository->findBy([], ['id' => 'DESC']);

        return $this->render('fournisseur/show.html.twig', [
            'fournisseurs' => $fournisseurs,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $fournisseur = new Fournisseur();

        $fournisseurForm = $this->createForm(FournisseurFormType::class, $fournisseur);

        $fournisseurForm->handleRequest($request);

        if($fournisseurForm->isSubmitted() && $fournisseurForm->isValid())
        {
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            $this->addFlash('success', "Le fournisseur a bien été ajouté !");

            return $this->redirectToRoute('admin.fournisseur.show');
        }

        return $this->render('fournisseur/add.html.twig', [
            'fournisseurForm' => $fournisseurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Fournisseur $fournisseur, EntityManagerInterface $entityManager, Request $request): Response
    {
        $fournisseurForm = $this->createForm(FournisseurFormType::class, $fournisseur);

        $fournisseurForm->handleRequest($request);

        if($fournisseurForm->isSubmitted() && $fournisseurForm->isValid())
        {
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            $this->addFlash('success', "Le fournisseur a bien été modifié !");

            return $this->redirectToRoute('admin.fournisseur.show');
        }

        return $this->render('fournisseur/edit.html.twig', [
            'fournisseurForm' => $fournisseurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Fournisseur $fournisseur, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$fournisseur->getId(), $request->get('_token')))
        {
            $entityManager->remove($fournisseur);
            $entityManager->flush();

            $this->addFlash('success', "Le fournisseur a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.fournisseur.show');
    }
}
