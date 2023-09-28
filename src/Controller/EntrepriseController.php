<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseFormType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/sites', name: 'admin.entreprise.')]
class EntrepriseController extends AbstractController
{
    private $repository;
    private $menu_active = "entreprise";

    public function __construct(EntrepriseRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('', name: 'show')]
    public function index(): Response
    {
        $entreprises = $this->repository->findBy([], ['id' => 'DESC']);

        return $this->render('entreprise/show.html.twig', [
            'entreprises' => $entreprises,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $entreprise = new Entreprise();

        $entrepriseForm = $this->createForm(EntrepriseFormType::class, $entreprise);

        $entrepriseForm->handleRequest($request);

        if($entrepriseForm->isSubmitted() && $entrepriseForm->isValid())
        {
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $this->addFlash('success', "Le site a bien été ajouté !");

            return $this->redirectToRoute('admin.entreprise.show');
        }

        return $this->render('entreprise/add.html.twig', [
            'entrepriseForm' => $entrepriseForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Entreprise $entreprise, EntityManagerInterface $entityManager, Request $request): Response
    {
        $entrepriseForm = $this->createForm(EntrepriseFormType::class, $entreprise);

        $entrepriseForm->handleRequest($request);

        if($entrepriseForm->isSubmitted() && $entrepriseForm->isValid())
        {
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $this->addFlash('success', "Le site a bien été modifié !");

            return $this->redirectToRoute('admin.entreprise.show');
        }

        return $this->render('entreprise/edit.html.twig', [
            'entrepriseForm' => $entrepriseForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Entreprise $entreprise, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->get('_token')))
        {
            $entityManager->remove($entreprise);
            $entityManager->flush();

            $this->addFlash('success', "Le site a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.entreprise.show');
    }
}
