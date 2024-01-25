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

#[Route('/parametres/stockages', name: 'admin.stockage.')]
class StockageController extends AbstractController
{
    private $menu_active = "stockage";

    /**
     * @param StockageRepository $stockageRepository
     * @return Response
     * Permet d'afficher la liste des types de stockage
     */
    #[Route('', name: 'show')]
    public function index(StockageRepository $stockageRepository): Response
    {
        $stockages = $stockageRepository->findBy([], ['id' => 'DESC']);

        return $this->render('stockage/show.html.twig', [
            'stockages' => $stockages,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet d'ajouter un type de stockage
     */
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

    /**
     * @param Stockage $stockage
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de modifier un type de stockage
     */
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

    /**
     * @param Stockage $stockage
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de supprimer un type de stockage
     */
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
