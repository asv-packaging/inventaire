<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $menu_active = "home";

    #[Route('/admin/accueil', name: 'admin.home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'menu_active' => $this->menu_active,
        ]);
    }
}
