<?php

namespace App\Controller;

use App\Entity\Telephone;
use App\Form\TelephoneFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/telephones', name: 'admin.telephone.')]
class TelephoneController extends AbstractController
{
    private $repository;
    private $menu_active = "telephone";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $telephones = $registry->getManager()->getRepository(Telephone::class)->createQueryBuilder('t')
            ->select('t.id, t.nom, t.marque, t.modele, etat.nom as etat_nom, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, t.numero_serie, t.imei1, t.imei2, t.date_achat, t.date_garantie, t.commentaire')
            ->leftJoin('t.utilisateur', 'utilisateur')
            ->leftJoin('t.etat', 'etat')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('telephone/show.html.twig', [
            'telephones' => $telephones,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $telephone = new Telephone();

        $telephoneForm = $this->createForm(TelephoneFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone a bien été ajouté !");

            return $this->redirectToRoute('admin.telephone.show');
        }

        return $this->render('telephone/add.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Telephone $telephone, EntityManagerInterface $entityManager, Request $request): Response
    {
        $telephoneForm = $this->createForm(TelephoneFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone a bien été modifié !");

            return $this->redirectToRoute('admin.telephone.show');
        }

        return $this->render('telephone/edit.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Telephone $telephone, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$telephone->getId(), $request->get('_token')))
        {
            $entityManager->remove($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.telephone.show');
    }
}
