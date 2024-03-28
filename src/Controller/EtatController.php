<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Form\EtatFormType;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/parametres/etats', name: 'admin.etat.')]
class EtatController extends AbstractController
{
    private $menu_active = "etat";

    /**
     * @param EtatRepository $etatRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des états
     */
    #[Route(name: 'show')]
    public function index(EtatRepository $etatRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $etatsRepo = $etatRepository->findBy([], ['id' => 'DESC']);

        $etats = $paginator->paginate(
            $etatsRepo,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('etat/show.html.twig', [
            'etats' => $etats,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet d'ajouter un état
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $etat = new Etat();

        $etatForm = $this->createForm(EtatFormType::class, $etat);

        $etatForm->handleRequest($request);

        if($etatForm->isSubmitted() && $etatForm->isValid())
        {
            $entityManager->persist($etat);
            $entityManager->flush();

            $this->addFlash('success', "L'état a été créé.");

            return $this->redirectToRoute('admin.etat.show');
        }

        return $this->render('etat/create.html.twig', [
            'etatForm' => $etatForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param Etat $etat
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de modifier un état
     */
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Etat $etat, EntityManagerInterface $entityManager, Request $request): Response
    {
        $etatForm = $this->createForm(EtatFormType::class, $etat);

        $etatForm->handleRequest($request);

        if($etatForm->isSubmitted() && $etatForm->isValid())
        {
            $entityManager->persist($etat);
            $entityManager->flush();

            $this->addFlash('success', "L'état a été modifié.");

            return $this->redirectToRoute('admin.etat.show');
        }

        return $this->render('etat/edit.html.twig', [
            'etatForm' => $etatForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param Etat $etat
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de supprimer un état
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Etat $etat, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$etat->getId(), $request->get('_token')))
        {
            $entityManager->remove($etat);
            $entityManager->flush();

            $this->addFlash('success', "L'état a été supprimé.");
        }

        return $this->redirectToRoute('admin.etat.show');
    }
}
