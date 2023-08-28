<?php

namespace App\Controller;

use App\Entity\TelephoneFixe;
use App\Form\TelephoneFixeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/telephones/fixes', name: 'admin.telephone_fixe.')]
class TelephoneFixeController extends AbstractController
{
    private $repository;
    private $menu_active = "telephone_fixe";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $telephones = $registry->getManager()->getRepository(TelephoneFixe::class)->createQueryBuilder('t')
            ->select('t.id, t.ligne, t.marque, t.modele, fournisseur.nom as fournisseur_nom, entreprise.nom as entreprise_nom, etat.nom as etat_nom, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, emplacement.nom as emplacement_nom, t.numero_serie, t.ip, t.type, t.date_achat, t.date_garantie, t.date_installation, t.commentaire')
            ->leftJoin('t.utilisateur', 'utilisateur')
            ->leftJoin('t.etat', 'etat')
            ->leftJoin('t.fournisseur', 'fournisseur')
            ->leftJoin('t.entreprise', 'entreprise')
            ->leftJoin('t.emplacement', 'emplacement')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('telephone_fixe/show.html.twig', [
            'telephones' => $telephones,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $telephone = new TelephoneFixe();

        $telephoneForm = $this->createForm(TelephoneFixeFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone fixe a bien été ajouté !");

            return $this->redirectToRoute('admin.telephone_fixe.show');
        }

        return $this->render('telephone_fixe/add.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(TelephoneFixe $telephone, EntityManagerInterface $entityManager, Request $request): Response
    {
        $telephoneForm = $this->createForm(TelephoneFixeFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone fixe a bien été modifié !");

            return $this->redirectToRoute('admin.telephone_fixe.show');
        }

        return $this->render('telephone_fixe/edit.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(TelephoneFixe $telephone, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$telephone->getId(), $request->get('_token')))
        {
            $entityManager->remove($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone fixe a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.telephone_fixe.show');
    }
}
