<?php

namespace App\Controller;

use App\Entity\Stockage;
use App\Form\StockageFormType;
use App\Repository\StockageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/stockages', name: 'admin.stockage.')]
class StockageController extends AbstractController
{
    private $repository;
    private $menu_active = "stockage";

    public function __construct(StockageRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('', name: 'show')]
    public function index(): Response
    {
        $stockages = $this->repository->findBy([], ['id' => 'DESC']);

        return $this->render('stockage/show.html.twig', [
            'stockages' => $stockages,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $stockage = new Stockage();

        $stockageForm = $this->createForm(StockageFormType::class, $stockage);

        $stockageForm->handleRequest($request);

        if($stockageForm->isSubmitted() && $stockageForm->isValid())
        {
            $entityManager->persist($stockage);
            $entityManager->flush();

            $this->addFlash('success', "Le type de stockage a bien été ajouté !");

            return $this->redirectToRoute('admin.stockage.show');
        }

        return $this->render('stockage/add.html.twig', [
            'stockageForm' => $stockageForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Stockage $stockage, EntityManagerInterface $entityManager, Request $request): Response
    {
        $stockageForm = $this->createForm(StockageFormType::class, $stockage);

        $stockageForm->handleRequest($request);

        if($stockageForm->isSubmitted() && $stockageForm->isValid())
        {
            $entityManager->persist($stockage);
            $entityManager->flush();

            $this->addFlash('success', "Le type de stockage a bien été modifié !");

            return $this->redirectToRoute('admin.stockage.show');
        }

        return $this->render('stockage/edit.html.twig', [
            'stockageForm' => $stockageForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Stockage $stockage, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$stockage->getId(), $request->get('_token')))
        {
            $entityManager->remove($stockage);
            $entityManager->flush();

            $this->addFlash('success', "Le type de stockage a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.stockage.show');
    }
}
