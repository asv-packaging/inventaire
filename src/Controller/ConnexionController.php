<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route('/', name: 'admin.connexion')]
    public function index(): Response
    {
        return $this->render('connexion/base.html.twig', [
        ]);
    }
}
