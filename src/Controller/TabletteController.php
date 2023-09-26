<?php

namespace App\Controller;

use App\Entity\Tablette;
use App\Form\TabletteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/tablettes', name: 'admin.tablette.')]
class TabletteController extends AbstractController
{
    private $repository;
    private $menu_active = "tablette";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $tablettes = $registry->getManager()->getRepository(Tablette::class)->createQueryBuilder('t')
            ->select('t.id, t.nom, t.marque, t.modele, emplacement.nom as emplacement_nom, entreprise.nom as entreprise_nom, etat.nom as etat_nom, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, t.numero_serie, t.ip, t.date_achat, t.date_garantie, t.commentaire')
            ->leftJoin('t.utilisateur', 'utilisateur')
            ->leftJoin('t.emplacement', 'emplacement')
            ->leftJoin('t.entreprise', 'entreprise')
            ->leftJoin('t.etat', 'etat')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('tablette/show.html.twig', [
            'tablettes' => $tablettes,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $tablette = new Tablette();

        $tabletteForm = $this->createForm(TabletteFormType::class, $tablette);

        $tabletteForm->handleRequest($request);

        if($tabletteForm->isSubmitted() && $tabletteForm->isValid())
        {
            $entityManager->persist($tablette);
            $entityManager->flush();

            $this->addFlash('success', "La tablette a bien été ajoutée !");

            return $this->redirectToRoute('admin.tablette.show');
        }

        return $this->render('tablette/add.html.twig', [
            'tabletteForm' => $tabletteForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Tablette $tablette, EntityManagerInterface $entityManager, Request $request): Response
    {
        $tabletteForm = $this->createForm(TabletteFormType::class, $tablette);

        $tabletteForm->handleRequest($request);

        if($tabletteForm->isSubmitted() && $tabletteForm->isValid())
        {
            $entityManager->persist($tablette);
            $entityManager->flush();

            $this->addFlash('success', "La tablette a bien été modifiée !");

            return $this->redirectToRoute('admin.tablette.show');
        }

        return $this->render('tablette/edit.html.twig', [
            'tabletteForm' => $tabletteForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Tablette $tablette, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$tablette->getId(), $request->get('_token')))
        {
            $entityManager->remove($tablette);
            $entityManager->flush();

            $this->addFlash('success', "La tablette a bien été supprimée !");
        }

        return $this->redirectToRoute('admin.tablette.show');
    }
}
