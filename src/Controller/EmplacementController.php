<?php

namespace App\Controller;

use App\Entity\Emplacement;
use App\Form\EmplacementFormType;
use App\Repository\EmplacementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/parametres/emplacements', name: 'admin.emplacement.')]
class EmplacementController extends AbstractController
{
    private $menu_active = "emplacement";

    /**
     * @param EmplacementRepository $emplacementRepository
     * @return Response
     * Permet d'afficher la liste des emplacements
     */
    #[Route('', name: 'show')]
    public function index(EmplacementRepository $emplacementRepository): Response
    {
        $emplacements = $emplacementRepository->findBy([], ['id' => 'DESC']);

        return $this->render('emplacement/show.html.twig', [
            'emplacements' => $emplacements,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet d'ajouter un emplacement
     */
    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $emplacement = new Emplacement();

        $emplacementForm = $this->createForm(EmplacementFormType::class, $emplacement);

        $emplacementForm->handleRequest($request);

        if($emplacementForm->isSubmitted() && $emplacementForm->isValid())
        {
            $entityManager->persist($emplacement);
            $entityManager->flush();

            $this->addFlash('success', "L'emplacement a bien été ajouté !");

            return $this->redirectToRoute('admin.emplacement.show');
        }

        return $this->render('emplacement/add.html.twig', [
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
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Emplacement $emplacement, EntityManagerInterface $entityManager, Request $request): Response
    {
        $emplacementForm = $this->createForm(EmplacementFormType::class, $emplacement);

        $emplacementForm->handleRequest($request);

        if($emplacementForm->isSubmitted() && $emplacementForm->isValid())
        {
            $entityManager->persist($emplacement);
            $entityManager->flush();

            $this->addFlash('success', "L'emplacement a bien été modifié !");

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

            $this->addFlash('success', "L'emplacement a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.emplacement.show');
    }
}
