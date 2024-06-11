<?php

namespace App\Controller;

use App\Entity\Stockage;
use App\Form\StockageFormType;
use App\Repository\StockageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    #[Route(name: 'show')]
    public function index(StockageRepository $stockageRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = $request->query->get('q');

        if ($search)
        {
            $stockagesRepo = $stockageRepository->findByCritere($search);
        }
        else
        {
            $stockagesRepo = $stockageRepository->findBy([], ['id' => 'DESC']);
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $stockages = $paginator->paginate(
            $stockagesRepo,
            $page,
            $limit
        );

        $total = $stockages->getTotalItemCount();

        return $this->render('stockage/show.html.twig', [
            'stockages' => $stockages,
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de créer un type de stockage
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $stockage = new Stockage();

        $stockageForm = $this->createForm(StockageFormType::class, $stockage);

        $stockageForm->handleRequest($request);

        if($stockageForm->isSubmitted() && $stockageForm->isValid())
        {
            $entityManager->persist($stockage);
            $entityManager->flush();

            $this->addFlash('success', "Le type de stockage a été créé.");

            return $this->redirectToRoute('admin.stockage.show');
        }

        return $this->render('stockage/create.html.twig', [
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

            $this->addFlash('success', "Le type de stockage a été modifié.");

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

            $this->addFlash('success', "Le type de stockage a été supprimé.");
        }

        return $this->redirectToRoute('admin.stockage.show');
    }
}
