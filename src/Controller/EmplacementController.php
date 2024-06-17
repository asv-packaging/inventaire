<?php

namespace App\Controller;

use App\Entity\Emplacement;
use App\Form\EmplacementFormType;
use App\Repository\EmplacementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/parametres/emplacements', name: 'admin.emplacement.')]
class EmplacementController extends AbstractController
{
    private $menu_active = "emplacement";

    /**
     * @param EmplacementRepository $emplacementRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des emplacements
     */
    #[Route(name: 'show')]
    public function index(EmplacementRepository $emplacementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = $request->query->get('q');

        if ($search)
        {
            $emplacementsRepo = $emplacementRepository->findByCritere($search);
        }
        else
        {
            $emplacementsRepo = $emplacementRepository->findBy([], ['id' => 'DESC']);
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $emplacements = $paginator->paginate(
            $emplacementsRepo,
            $page,
            $limit
        );

        $total = $emplacements->getTotalItemCount();

        return $this->render('emplacement/show.html.twig', [
            'emplacements' => $emplacements,
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
     * Permet de créer un emplacement
     */
    #[Route('/creer', name: 'create')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $emplacement = new Emplacement();

        $emplacementForm = $this->createForm(EmplacementFormType::class, $emplacement);

        $emplacementForm->handleRequest($request);

        if($emplacementForm->isSubmitted() && $emplacementForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $emplacementForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $emplacement->setSlug($slug);

            $entityManager->persist($emplacement);
            $entityManager->flush();

            $this->addFlash('success', "L'emplacement a été créé.");

            return $this->redirectToRoute('admin.emplacement.show');
        }

        return $this->render('emplacement/create.html.twig', [
            'emplacementForm' => $emplacementForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param Emplacement $emplacement
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de modifier un emplacement
     */
    #[Route('/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(#[MapEntity(mapping: ['slug' => 'slug'])] Emplacement $emplacement, EntityManagerInterface $entityManager, Request $request): Response
    {
        $emplacementForm = $this->createForm(EmplacementFormType::class, $emplacement);

        $emplacementForm->handleRequest($request);

        if($emplacementForm->isSubmitted() && $emplacementForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $emplacementForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $emplacement->setSlug($slug);

            $entityManager->persist($emplacement);
            $entityManager->flush();

            $this->addFlash('success', "L'emplacement a été modifié.");

            return $this->redirectToRoute('admin.emplacement.show');
        }

        return $this->render('emplacement/edit.html.twig', [
            'emplacementForm' => $emplacementForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param Emplacement $emplacement
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de supprimer un emplacement
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Emplacement $emplacement, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$emplacement->getId(), $request->get('_token')))
        {
            $entityManager->remove($emplacement);
            $entityManager->flush();

            $this->addFlash('success', "L'emplacement a été supprimé.");
        }

        return $this->redirectToRoute('admin.emplacement.show');
    }
}
