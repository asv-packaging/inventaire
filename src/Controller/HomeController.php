<?php

namespace App\Controller;

use App\Entity\Ecran;
use App\Entity\Emplacement;
use App\Entity\Entreprise;
use App\Entity\Etat;
use App\Entity\Fournisseur;
use App\Entity\Imprimante;
use App\Entity\Onduleur;
use App\Entity\PcFixe;
use App\Entity\PcPortable;
use App\Entity\Serveur;
use App\Entity\Stockage;
use App\Entity\SystemeExploitation;
use App\Entity\Tablette;
use App\Entity\TelephoneFixe;
use App\Entity\TelephonePortable;
use App\Entity\Utilisateur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $menu_active = "home";

    #[Route('/admin/accueil', name: 'admin.home')]
    public function index(ManagerRegistry $registry): Response
    {
        // Gestions des statistiques pour le matériel
        $ecrans = count($registry->getManager()->getRepository(Ecran::class)->findAll());
        $imprimantes = count($registry->getManager()->getRepository(Imprimante::class)->findAll());
        $onduleurs = count($registry->getManager()->getRepository(Onduleur::class)->findAll());
        $pc_fixes = count($registry->getManager()->getRepository(PcFixe::class)->findAll());
        $pc_portable = count($registry->getManager()->getRepository(PcPortable::class)->findAll());
        $serveurs = count($registry->getManager()->getRepository(Serveur::class)->findAll());
        $tablettes = count($registry->getManager()->getRepository(Tablette::class)->findAll());
        $telephone_fixes = count($registry->getManager()->getRepository(TelephoneFixe::class)->findAll());
        $telephone_portables = count($registry->getManager()->getRepository(TelephonePortable::class)->findAll());

        // Gestions des statistiques pour les paramètres
        $emplacements = count($registry->getManager()->getRepository(Emplacement::class)->findAll());
        $etats = count($registry->getManager()->getRepository(Etat::class)->findAll());
        $fournisseurs = count($registry->getManager()->getRepository(Fournisseur::class)->findAll());
        $sites = count($registry->getManager()->getRepository(Entreprise::class)->findAll());
        $systemes_exploitation = count($registry->getManager()->getRepository(SystemeExploitation::class)->findAll());
        $stockage = count($registry->getManager()->getRepository(Stockage::class)->findAll());
        $utilisateurs = count($registry->getManager()->getRepository(Utilisateur::class)->findAll());

        return $this->render('home/index.html.twig', [
            'menu_active' => $this->menu_active,
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