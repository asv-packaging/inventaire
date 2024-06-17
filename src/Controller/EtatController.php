<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Form\EtatFormType;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

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
        $search = $request->query->get('q');

        if ($search)
        {
            $etatsRepo = $etatRepository->findByCritere($search);
        }
        else
        {
            $etatsRepo = $etatRepository->findBy([], ['id' => 'DESC']);
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $etats = $paginator->paginate(
            $etatsRepo,
            $page,
            $limit
        );

        $total = $etats->getTotalItemCount();

        return $this->render('etat/show.html.twig', [
            'etats' => $etats,
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
            $slugger = new AsciiSlugger();
            $nom = $etatForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $etat->setSlug($slug);

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
    #[Route('/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(#[MapEntity(mapping: ['slug' => 'slug'])] Etat $etat, EntityManagerInterface $entityManager, Request $request): Response
    {
        $etatForm = $this->createForm(EtatFormType::class, $etat);

        $etatForm->handleRequest($request);

        if($etatForm->isSubmitted() && $etatForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $etatForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $etat->setSlug($slug);

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
