<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseFormType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/parametres/sites', name: 'admin.entreprise.')]
class EntrepriseController extends AbstractController
{
    private $menu_active = "entreprise";

    /**
     * @param EntrepriseRepository $entrepriseRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des entreprises (sites)
     */
    #[Route(name: 'show')]
    public function index(EntrepriseRepository $entrepriseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $entreprisesRepo = $entrepriseRepository->findBy([], ['id' => 'DESC']);

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $entreprises = $paginator->paginate(
            $entreprisesRepo,
            $page,
            $limit
        );

        $total = $entreprises->getTotalItemCount();

        return $this->render('entreprise/show.html.twig', [
            'entreprises' => $entreprises,
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
     * Permet de créer une entreprise (site)
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $entreprise = new Entreprise();

        $entrepriseForm = $this->createForm(EntrepriseFormType::class, $entreprise);

        $entrepriseForm->handleRequest($request);

        if($entrepriseForm->isSubmitted() && $entrepriseForm->isValid())
        {
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $this->addFlash('success', "Le site a été créé.");

            return $this->redirectToRoute('admin.entreprise.show');
        }

        return $this->render('entreprise/create.html.twig', [
            'entrepriseForm' => $entrepriseForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param Entreprise $entreprise
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de modifier une entreprise (site)
     */
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Entreprise $entreprise, EntityManagerInterface $entityManager, Request $request): Response
    {
        $entrepriseForm = $this->createForm(EntrepriseFormType::class, $entreprise);

        $entrepriseForm->handleRequest($request);

        if($entrepriseForm->isSubmitted() && $entrepriseForm->isValid())
        {
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $this->addFlash('success', "Le site a été modifié.");

            return $this->redirectToRoute('admin.entreprise.show');
        }

        return $this->render('entreprise/edit.html.twig', [
            'entrepriseForm' => $entrepriseForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param Entreprise $entreprise
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de supprimer une entreprise (site)
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Entreprise $entreprise, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->get('_token')))
        {
            $entityManager->remove($entreprise);
            $entityManager->flush();

            $this->addFlash('success', "Le site a été supprimé.");
        }

        return $this->redirectToRoute('admin.entreprise.show');
    }
}
