<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurFormType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/utilisateurs', name: 'admin.utilisateur.')]
class UtilisateurController extends AbstractController
{
    private $repository;
    private $menu_active = "utilisateur";

    public function __construct(UtilisateurRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $utilisateurs = $registry->getManager()->getRepository(Utilisateur::class)->createQueryBuilder('u')
            ->select('u.id, u.nom, u.prenom, u.email, entreprise.nom as entreprise_nom')
            ->leftJoin('u.entreprise', 'entreprise')
            ->orderBy('u.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('utilisateur/show.html.twig', [
            'utilisateurs' => $utilisateurs,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $utilisateur = new Utilisateur();

        $utilisateurForm = $this->createForm(UtilisateurFormType::class, $utilisateur);

        $utilisateurForm->handleRequest($request);

        if($utilisateurForm->isSubmitted() && $utilisateurForm->isValid())
        {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté !");

            return $this->redirectToRoute('admin.utilisateur.show');
        }

        return $this->render('utilisateur/add.html.twig', [
            'utilisateurForm' => $utilisateurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Utilisateur $utilisateur, EntityManagerInterface $entityManager, Request $request): Response
    {
        $utilisateurForm = $this->createForm(UtilisateurFormType::class, $utilisateur);

        $utilisateurForm->handleRequest($request);

        if($utilisateurForm->isSubmitted() && $utilisateurForm->isValid())
        {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié !");

            return $this->redirectToRoute('admin.utilisateur.show');
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateurForm' => $utilisateurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Utilisateur $utilisateur, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->get('_token')))
        {
            $entityManager->remove($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.utilisateur.show');
    }
}
