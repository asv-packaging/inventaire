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
use App\Repository\TelephoneRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private $menu_active = "home";


    #[Route('/accueil', name: 'admin.home')]
    public function index(
        Security $security, EcranRepository $ecranRepository, ImprimanteRepository $imprimanteRepository, OnduleurRepository $onduleurRepository,
        PcFixeRepository $pcFixeRepository, PcPortableRepository $pcPortableRepository, ServeurRepository $serveurRepository,
        TabletteRepository $tabletteRepository, TelephoneFixeRepository $telephoneFixeRepository, TelephoneRepository $telephonePortableRepository,
        EmplacementRepository $emplacementRepository, EtatRepository $etatRepository, FournisseurRepository $fournisseurRepository,
        EntrepriseRepository $entrepriseRepository, SystemeExploitationRepository $systemeExploitationRepository, StockageRepository $stockageRepository,
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
}