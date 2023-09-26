<?php

namespace App\Controller;

use App\Entity\Onduleur;
use App\Form\OnduleurFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/onduleurs', name: 'admin.onduleur.')]
class OnduleurController extends AbstractController
{
    private $repository;
    private $menu_active = "onduleur";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $onduleurs = $registry->getManager()->getRepository(Onduleur::class)->createQueryBuilder('o')
            ->select('o.id, o.nom, o.marque, o.modele, emplacement.nom as emplacement_nom, etat.nom as etat_nom, entreprise.nom as entreprise_nom, fournisseur.nom as fournisseur_nom, o.numero_serie, o.capacite, o.type_prise, o.date_installation, o.date_achat, o.date_garantie, o.commentaire')
            ->leftJoin('o.emplacement', 'emplacement')
            ->leftJoin('o.etat', 'etat')
            ->leftJoin('o.entreprise', 'entreprise')
            ->leftJoin('o.fournisseur', 'fournisseur')
            ->orderBy('o.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('onduleur/show.html.twig', [
            'onduleurs' => $onduleurs,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $onduleur = new Onduleur();

        $onduleurForm = $this->createForm(OnduleurFormType::class, $onduleur);

        $onduleurForm->handleRequest($request);

        if($onduleurForm->isSubmitted() && $onduleurForm->isValid())
        {
            $entityManager->persist($onduleur);
            $entityManager->flush();

            $this->addFlash('success', "L'onduleur a bien été ajouté !");

            return $this->redirectToRoute('admin.onduleur.show');
        }

        return $this->render('onduleur/add.html.twig', [
            'onduleurForm' => $onduleurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Onduleur $onduleur, EntityManagerInterface $entityManager, Request $request): Response
    {
        $onduleurForm = $this->createForm(OnduleurFormType::class, $onduleur);

        $onduleurForm->handleRequest($request);

        if($onduleurForm->isSubmitted() && $onduleurForm->isValid())
        {
            $entityManager->persist($onduleur);
            $entityManager->flush();

            $this->addFlash('success', "L'onduleur a bien été modifié !");

            return $this->redirectToRoute('admin.onduleur.show');
        }

        return $this->render('onduleur/edit.html.twig', [
            'onduleurForm' => $onduleurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Onduleur $onduleur, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$onduleur->getId(), $request->get('_token')))
        {
            $entityManager->remove($onduleur);
            $entityManager->flush();

            $this->addFlash('success', "L'onduleur a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.onduleur.show');
    }
}
