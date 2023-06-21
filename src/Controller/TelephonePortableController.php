<?php

namespace App\Controller;

use App\Entity\TelephonePortable;
use App\Form\TelephonePortableFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/telephones/portables', name: 'admin.telephone_portable.')]
class TelephonePortableController extends AbstractController
{
    private $repository;
    private $menu_active = "telephone_portable";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $telephones = $registry->getManager()->getRepository(TelephonePortable::class)->createQueryBuilder('t')
            ->select('t.id, t.nom, t.marque, t.modele, etat.nom as etat_nom, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, t.numero_serie, t.imei1, t.imei2, t.date_achat, t.date_garantie, t.commentaire')
            ->leftJoin('t.utilisateur', 'utilisateur')
            ->leftJoin('t.etat', 'etat')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('telephone_portable/show.html.twig', [
            'telephones' => $telephones,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $telephone = new TelephonePortable();

        $telephoneForm = $this->createForm(TelephonePortableFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone portable a bien été ajouté !");

            return $this->redirectToRoute('admin.telephone_portable.show');
        }

        return $this->render('telephone_portable/add.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(TelephonePortable $telephone, EntityManagerInterface $entityManager, Request $request): Response
    {
        $telephoneForm = $this->createForm(TelephonePortableFormType::class, $telephone);

        $telephoneForm->handleRequest($request);

        if($telephoneForm->isSubmitted() && $telephoneForm->isValid())
        {
            $entityManager->persist($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone portable a bien été modifié !");

            return $this->redirectToRoute('admin.telephone_portable.show');
        }

        return $this->render('telephone_portable/edit.html.twig', [
            'telephoneForm' => $telephoneForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(TelephonePortable $telephone, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$telephone->getId(), $request->get('_token')))
        {
            $entityManager->remove($telephone);
            $entityManager->flush();

            $this->addFlash('success', "Le téléphone portable a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.telephone_portable.show');
    }
}
