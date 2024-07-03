<?php

namespace App\Controller;

use App\Form\ProfilFormType;
use App\Form\ProfilPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profil', name: 'admin.profil.')]
class ProfilController extends AbstractController
{
    #[Route(name: 'show')]
    public function index(Security $security, EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $utilisateur = $security->getUser();

        $profilForm = $this->createForm(ProfilFormType::class, $utilisateur);
        $profilForm->handleRequest($request);

        if ($profilForm->isSubmitted() && $profilForm->isValid())
        {
            $entityManager->flush();

            $this->addFlash('success-information', 'Vos informations ont bien été modifiées !');

            return $this->redirectToRoute('admin.profil.show');
        }

        $profilPasswordForm = $this->createForm(ProfilPasswordType::class, $utilisateur);
        $profilPasswordForm->handleRequest($request);

        if ($profilPasswordForm->isSubmitted() && $profilPasswordForm->isValid())
        {
            $utilisateur->setPassword($hasher->hashPassword($utilisateur, $profilPasswordForm->get('password')->getData()));

            $entityManager->flush();

            $this->addFlash('success-password', 'Votre mot de passe a bien été modifié !');

            return $this->redirectToRoute('admin.profil.show');
        }

        return $this->render('profil/show.html.twig', [
            'utilisateur' => $utilisateur,
            'profilForm' => $profilForm->createView(),
            'profilPasswordForm' => $profilPasswordForm->createView(),
        ]);
    }
}
