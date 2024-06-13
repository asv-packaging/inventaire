<?php

namespace App\Controller;

use App\Repository\EcranRepository;
use App\Repository\EmplacementRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\EtatRepository;
use App\Repository\FournisseurRepository;
use App\Repository\ImprimanteRepository;
use App\Repository\OnduleurRepository;
use App\Repository\PcFixeRepository;
use App\Repository\PcPortableRepository;
use App\Repository\ServeurRepository;
use App\Repository\StockageRepository;
use App\Repository\SystemeExploitationRepository;
use App\Repository\TabletteRepository;
use App\Repository\TelephoneFixeRepository;
use App\Repository\TelephonePortableRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\AsciiSlugger;

class HomeController extends AbstractController
{
    private $menu_active = "home";

    #[Route('/accueil', name: 'admin.home')]
    public function index(
        Security              $security, EcranRepository $ecranRepository, ImprimanteRepository $imprimanteRepository, OnduleurRepository $onduleurRepository,
        PcFixeRepository      $pcFixeRepository, PcPortableRepository $pcPortableRepository, ServeurRepository $serveurRepository,
        TabletteRepository    $tabletteRepository, TelephoneFixeRepository $telephoneFixeRepository, TelephonePortableRepository $telephonePortableRepository,
        EmplacementRepository $emplacementRepository, EtatRepository $etatRepository, FournisseurRepository $fournisseurRepository,
        EntrepriseRepository  $entrepriseRepository, SystemeExploitationRepository $systemeExploitationRepository, StockageRepository $stockageRepository,
        UtilisateurRepository $utilisateurRepository): Response
    {
        // Récupération des informations de l'utilisateur connecté
        $utilisateur = $security->getUser();

        // Gestions des statistiques pour le matériel
        $ecrans = $ecranRepository->countEcrans();
        $imprimantes = $imprimanteRepository->countImprimantes();
        $onduleurs = $onduleurRepository->countOnduleurs();
        $pc_fixes = $pcFixeRepository->countPcFixes();
        $pc_portable = $pcPortableRepository->countPcPortables();
        $serveurs = $serveurRepository->countServeurs();
        $tablettes = $tabletteRepository->countTablettes();
        $telephone_fixes = $telephoneFixeRepository->countTelephoneFixes();
        $telephone_portables = $telephonePortableRepository->countTelephonePortables();

        // Gestions des statistiques pour les paramètres
        $emplacements = $emplacementRepository->countEmplacements();
        $etats = $etatRepository->countEtats();
        $fournisseurs = $fournisseurRepository->countFournisseurs();
        $sites = $entrepriseRepository->countEntreprises();
        $systemes_exploitation = $systemeExploitationRepository->countSystemeExploitations();
        $stockage = $stockageRepository->countStockages();
        $utilisateurs = $utilisateurRepository->countUtilisateurs();




        return $this->render('home/index.html.twig', [
            'menu_active' => $this->menu_active,
            'utilisateur' => $utilisateur,
            'ecrans' => $ecrans,
            'imprimantes' => $imprimantes,
            'onduleurs' => $onduleurs,
            'pc_fixes' => $pc_fixes,
            'pc_portable' => $pc_portable,
            'serveurs' => $serveurs,
            'tablettes' => $tablettes,
            'telephone_fixes' => $telephone_fixes,
            'telephone_portables' => $telephone_portables,
            'emplacements' => $emplacements,
            'etats' => $etats,
            'fournisseurs' => $fournisseurs,
            'sites' => $sites,
            'systemes_exploitation' => $systemes_exploitation,
            'stockage' => $stockage,
            'utilisateurs' => $utilisateurs,
        ]);
    }

    #[Route('/slug', name: 'admin.slug')]
    public function slug(
        EcranRepository $ecranRepository, ImprimanteRepository $imprimanteRepository, OnduleurRepository $onduleurRepository,
        PcFixeRepository      $pcFixeRepository, PcPortableRepository $pcPortableRepository, ServeurRepository $serveurRepository,
        TabletteRepository    $tabletteRepository, TelephoneFixeRepository $telephoneFixeRepository, TelephonePortableRepository $telephonePortableRepository,
        EmplacementRepository $emplacementRepository, EtatRepository $etatRepository, FournisseurRepository $fournisseurRepository,
        EntrepriseRepository  $entrepriseRepository, SystemeExploitationRepository $systemeExploitationRepository, StockageRepository $stockageRepository,
        UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager): Response
    {
        $ecrans = $ecranRepository->findAll();
        $imprimantes = $imprimanteRepository->findAll();
        $onduleurs = $onduleurRepository->findAll();
        $pc_fixes = $pcFixeRepository->findAll();
        $pc_portable = $pcPortableRepository->findAll();
        $serveurs = $serveurRepository->findAll();
        $tablettes = $tabletteRepository->findAll();
        $telephone_fixes = $telephoneFixeRepository->findAll();
        $telephone_portables = $telephonePortableRepository->findAll();
        $emplacements = $emplacementRepository->findAll();
        $etats = $etatRepository->findAll();
        $fournisseurs = $fournisseurRepository->findAll();
        $sites = $entrepriseRepository->findAll();
        $systemes_exploitation = $systemeExploitationRepository->findAll();
        $stockage = $stockageRepository->findAll();
        $utilisateurs = $utilisateurRepository->findAll();

        foreach ($ecrans as $ecran)
        {
            $slugger = new AsciiSlugger();
            $nom = $ecran->getNom();
            $slug = strtolower($slugger->slug($nom));

            $ecran->setSlug($slug);
        }

        foreach ($imprimantes as $imprimante)
        {
            $slugger = new AsciiSlugger();
            $nom = $imprimante->getNom();
            $slug = strtolower($slugger->slug($nom));

            $imprimante->setSlug($slug);
        }

        foreach ($onduleurs as $onduleur)
        {
            $slugger = new AsciiSlugger();
            $nom = $onduleur->getNom();
            $slug = strtolower($slugger->slug($nom));

            $onduleur->setSlug($slug);
        }

        foreach ($pc_fixes as $pc_fixe)
        {
            $slugger = new AsciiSlugger();
            $nom = $pc_fixe->getNom();
            $slug = strtolower($slugger->slug($nom));

            $pc_fixe->setSlug($slug);
        }

        foreach ($pc_portable as $pc_portable)
        {
            $slugger = new AsciiSlugger();
            $nom = $pc_portable->getNom();
            $slug = strtolower($slugger->slug($nom));

            $pc_portable->setSlug($slug);
        }

        foreach ($serveurs as $serveur)
        {
            $slugger = new AsciiSlugger();
            $nom = $serveur->getNom();
            $slug = strtolower($slugger->slug($nom));

            $serveur->setSlug($slug);
        }

        foreach ($tablettes as $tablette)
        {
            $slugger = new AsciiSlugger();
            $nom = $tablette->getNom();
            $slug = strtolower($slugger->slug($nom));

            $tablette->setSlug($slug);
        }

        foreach ($telephone_fixes as $telephone_fixe)
        {
            $slugger = new AsciiSlugger();
            $nom = $telephone_fixe->getLigne();
            $slug = strtolower($slugger->slug($nom));

            $telephone_fixe->setSlug($slug);
        }

        foreach ($telephone_portables as $telephone_portable)
        {
            $slugger = new AsciiSlugger();
            $nom = $telephone_portable->getLigne();
            $slug = strtolower($slugger->slug($nom));

            $telephone_portable->setSlug($slug);
        }

        foreach ($emplacements as $emplacement)
        {
            $slugger = new AsciiSlugger();
            $nom = $emplacement->getNom();
            $slug = strtolower($slugger->slug($nom));

            $emplacement->setSlug($slug);
        }

        foreach ($etats as $etat)
        {
            $slugger = new AsciiSlugger();
            $nom = $etat->getNom();
            $slug = strtolower($slugger->slug($nom));

            $etat->setSlug($slug);
        }

        foreach ($fournisseurs as $fournisseur)
        {
            $slugger = new AsciiSlugger();
            $nom = $fournisseur->getNom();
            $slug = strtolower($slugger->slug($nom));

            $fournisseur->setSlug($slug);
        }

        foreach ($sites as $site)
        {
            $slugger = new AsciiSlugger();
            $nom = $site->getNom();
            $slug = strtolower($slugger->slug($nom));

            $site->setSlug($slug);
        }

        foreach ($systemes_exploitation as $systeme_exploitation)
        {
            $slugger = new AsciiSlugger();
            $nom = $systeme_exploitation->getNom();
            $slug = strtolower($slugger->slug($nom));

            $systeme_exploitation->setSlug($slug);
        }

        foreach ($stockage as $stock)
        {
            $slugger = new AsciiSlugger();
            $nom = $stock->getNom();
            $slug = strtolower($slugger->slug($nom));

            $stock->setSlug($slug);
        }

        foreach ($utilisateurs as $utilisateur)
        {
            $slugger = new AsciiSlugger();
            $nom = $utilisateur->getNom().' '.$utilisateur->getPrenom();
            $slug = strtolower($slugger->slug($nom));

            $utilisateur->setSlug($slug);
        }

        $entityManager->flush();

        return $this->redirectToRoute('admin.home');
    }
}