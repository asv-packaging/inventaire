<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurFormType;
use App\Repository\UtilisateurRepository;
use App\Service\ExcelExportService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/parametres/utilisateurs', name: 'admin.utilisateur.')]
class UtilisateurController extends AbstractController
{
    private $menu_active = "utilisateur";

    /**
     * @param UtilisateurRepository $utilisateurRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des utilisateurs
     */
    #[Route(name: 'show')]
    public function index(UtilisateurRepository $utilisateurRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $recherche = $request->query->get('par');
        $search = $request->query->get('q');

        if ($recherche && $search)
        {
            $utilisateursRepo = $utilisateurRepository->findByCritere($recherche, $search);
        }
        else
        {
            $utilisateursRepo = $utilisateurRepository->findBy([], ['nom' => 'ASC']);
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $utilisateurs = $paginator->paginate(
            $utilisateursRepo,
            $page,
            $limit
        );

        $total = $utilisateurs->getTotalItemCount();

        return $this->render('utilisateur/show.html.twig', [
            'utilisateurs' => $utilisateurs,
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param ExcelExportService $excelExportService
     * @param UtilisateurRepository $utilisateurRepository
     * @return Response
     * Permet d'exporter les données des utilisateurs au format Excel
     */
    #[Route('/exporter', name: 'export')]
    public function exportDataToExcel(ExcelExportService $excelExportService, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateurs = $utilisateurRepository->findAll();

        if(count($utilisateurs) !== 0)
        {
            $headers = ['ID', 'Nom', 'Prénom', 'Email', 'Site'];
            $data = [];

            foreach($utilisateurs as $utilisateur)
            {
                $data[] = [
                    $utilisateur->getId(),
                    $utilisateur->getNom(),
                    $utilisateur->getPrenom(),
                    $utilisateur->getEmail() ?? 'N/A',
                    $utilisateur->getEntreprise() !== null ? $utilisateur->getEntreprise()->getNom() : 'N/A',
                ];
            }

            // Chemin où sauvegarder le fichier Excel
            $filePath = $this->getParameter('kernel.project_dir') . '/var/export_data_inventaire_utilisateurs.xlsx';

            // Utilise le service pour exporter les données
            $excelExportService->exportToExcel($headers, $data, $filePath);

            // Télécharge le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inventaire_utilisateurs.xlsx');
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            // Supprime le fichier après le téléchargement
            register_shutdown_function(function () use ($filePath)
            {
                if (file_exists($filePath))
                {
                    unlink($filePath);
                }
            });

            return $response;
        }
        else
        {
            $this->addFlash('danger', "Vous ne pouvez pas exporter les données car aucun utilisateur n'a été trouvé !");

            return $this->redirectToRoute('admin.utilisateur.show');
        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de créer un utilisateur
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $utilisateur = new Utilisateur();

        $utilisateurForm = $this->createForm(UtilisateurFormType::class, $utilisateur);

        $utilisateurForm->handleRequest($request);

        if($utilisateurForm->isSubmitted() && $utilisateurForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $utilisateurForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $utilisateur->setSlug($slug);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été créé.");

            return $this->redirectToRoute('admin.utilisateur.show');
        }

        return $this->render('utilisateur/create.html.twig', [
            'utilisateurForm' => $utilisateurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param Utilisateur $utilisateur
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de modifier un utilisateur
     */
    #[Route('/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(#[MapEntity(mapping: ['slug' => 'slug'])] Utilisateur $utilisateur, EntityManagerInterface $entityManager, Request $request): Response
    {
        $utilisateurForm = $this->createForm(UtilisateurFormType::class, $utilisateur);

        $utilisateurForm->handleRequest($request);

        if($utilisateurForm->isSubmitted() && $utilisateurForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $utilisateurForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $utilisateur->setSlug($slug);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été modifié.");

            return $this->redirectToRoute('admin.utilisateur.show');
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateurForm' => $utilisateurForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param Utilisateur $utilisateur
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de supprimer un utilisateur
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Utilisateur $utilisateur, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->get('_token')))
        {
            $entityManager->remove($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été supprimé.");
        }

        return $this->redirectToRoute('admin.utilisateur.show');
    }
}
