<?php

namespace App\Controller;

use App\Entity\Imprimante;
use App\Form\ImprimanteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/imprimantes', name: 'admin.imprimante.')]
class ImprimanteController extends AbstractController
{
    private $repository;
    private $menu_active = "imprimante";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $imprimantes = $registry->getManager()->getRepository(Imprimante::class)->createQueryBuilder('i')
            ->select('i.id, i.nom, i.marque, i.modele, emplacement.nom as emplacement_nom, etat.nom as etat_nom, entreprise.nom as entreprise_nom, fournisseur.nom as fournisseur_nom, i.numero_serie, i.ip, i.contrat, i.date_installation, i.date_achat, i.date_garantie, i.commentaire')
            ->leftJoin('i.emplacement', 'emplacement')
            ->leftJoin('i.etat', 'etat')
            ->leftJoin('i.entreprise', 'entreprise')
            ->leftJoin('i.fournisseur', 'fournisseur')
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('imprimante/show.html.twig', [
            'imprimantes' => $imprimantes,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $imprimante = new Imprimante();

        $imprimanteForm = $this->createForm(ImprimanteFormType::class, $imprimante);

        $imprimanteForm->handleRequest($request);

        if($imprimanteForm->isSubmitted() && $imprimanteForm->isValid())
        {
            $entityManager->persist($imprimante);
            $entityManager->flush();

            $this->addFlash('success', "L'imprimante a bien été ajouté !");

            return $this->redirectToRoute('admin.imprimante.show');
        }

        return $this->render('imprimante/add.html.twig', [
            'imprimanteForm' => $imprimanteForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Imprimante $imprimante, EntityManagerInterface $entityManager, Request $request): Response
    {
        $imprimanteForm = $this->createForm(ImprimanteFormType::class, $imprimante);

        $imprimanteForm->handleRequest($request);

        if($imprimanteForm->isSubmitted() && $imprimanteForm->isValid())
        {
            $entityManager->persist($imprimante);
            $entityManager->flush();

            $this->addFlash('success', "L'imprimante a bien été modifié !");

            return $this->redirectToRoute('admin.imprimante.show');
        }

        return $this->render('imprimante/edit.html.twig', [
            'imprimanteForm' => $imprimanteForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Imprimante $imprimante, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$imprimante->getId(), $request->get('_token')))
        {
            $entityManager->remove($imprimante);
            $entityManager->flush();

            $this->addFlash('success', "L'imprimante a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.imprimante.show');
    }
}
