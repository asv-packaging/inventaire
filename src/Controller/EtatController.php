<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Form\EtatFormType;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/etats', name: 'admin.etat.')]
class EtatController extends AbstractController
{
    private $repository;
    private $menu_active = "etat";

    public function __construct(EtatRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('', name: 'show')]
    public function index(): Response
    {
        $etats = $this->repository->findBy([], ['id' => 'DESC']);

        return $this->render('etat/show.html.twig', [
            'etats' => $etats,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $etat = new Etat();

        $etatForm = $this->createForm(EtatFormType::class, $etat);

        $etatForm->handleRequest($request);

        if($etatForm->isSubmitted() && $etatForm->isValid())
        {
            $entityManager->persist($etat);
            $entityManager->flush();

            $this->addFlash('success', "L'état a bien été ajouté !");

            return $this->redirectToRoute('admin.etat.show');
        }

        return $this->render('etat/add.html.twig', [
            'etatForm' => $etatForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Etat $etat, EntityManagerInterface $entityManager, Request $request): Response
    {
        $etatForm = $this->createForm(EtatFormType::class, $etat);

        $etatForm->handleRequest($request);

        if($etatForm->isSubmitted() && $etatForm->isValid())
        {
            $entityManager->persist($etat);
            $entityManager->flush();

            $this->addFlash('success', "L'état a bien été modifié !");

            return $this->redirectToRoute('admin.etat.show');
        }

        return $this->render('etat/edit.html.twig', [
            'etatForm' => $etatForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Etat $etat, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$etat->getId(), $request->get('_token')))
        {
            $entityManager->remove($etat);
            $entityManager->flush();

            $this->addFlash('success', "L'état a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.etat.show');
    }
}
