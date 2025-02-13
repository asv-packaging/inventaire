<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurFormType;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/parametres/fournisseurs', name: 'admin.fournisseur.')]
class FournisseurController extends AbstractController
{
    private $menu_active = "fournisseur";

    /**
     * @param FournisseurRepository $fournisseurRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des fournisseurs
     */
    #[Route(name: 'show')]
    public function index(FournisseurRepository $fournisseurRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = $request->query->get('q');

        if ($search)
        {
            $fournisseursRepo = $fournisseurRepository->findByCritere($search);
        }
        else
        {
            $fournisseursRepo = $fournisseurRepository->findBy([], ['id' => 'DESC']);
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $fournisseurs = $paginator->paginate(
            $fournisseursRepo,
            $page,
            $limit
        );

        $total = $fournisseurs->getTotalItemCount();

        return $this->render('fournisseur/show.html.twig', [
            'fournisseurs' => $fournisseurs,
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
     * Permet de créer un fournisseur
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $fournisseur = new Fournisseur();

        $fournisseurForm = $this->createForm(FournisseurFormType::class, $fournisseur);

        $fournisseurForm->handleRequest($request);

        if($fournisseurForm->isSubmitted() && $fournisseurForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $fournisseurForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $fournisseur->setSlug($slug);

            $entityManager->persist($fournisseur);
            $entityManager->flush();

            $this->addFlash('success', "Le fournisseur a été ajouté.");

            return $this->redirectToRoute('admin.fournisseur.show');
        }

        return $this->render('fournisseur/create.html.twig', [
            'fournisseurForm' => $fournisseurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param Fournisseur $fournisseur
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de modifier un fournisseur
     */
    #[Route('/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(#[MapEntity(mapping: ['slug' => 'slug'])] Fournisseur $fournisseur, EntityManagerInterface $entityManager, Request $request): Response
    {
        $fournisseurForm = $this->createForm(FournisseurFormType::class, $fournisseur);

        $fournisseurForm->handleRequest($request);

        if($fournisseurForm->isSubmitted() && $fournisseurForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $fournisseurForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $fournisseur->setSlug($slug);

            $entityManager->persist($fournisseur);
            $entityManager->flush();

            $this->addFlash('success', "Le fournisseur a été modifié.");

            return $this->redirectToRoute('admin.fournisseur.show');
        }

        return $this->render('fournisseur/edit.html.twig', [
            'fournisseurForm' => $fournisseurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param Fournisseur $fournisseur
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de supprimer un fournisseur
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Fournisseur $fournisseur, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$fournisseur->getId(), $request->get('_token')))
        {
            $entityManager->remove($fournisseur);
            $entityManager->flush();

            $this->addFlash('success', "Le fournisseur a été supprimé.");
        }

        return $this->redirectToRoute('admin.fournisseur.show');
    }
}
