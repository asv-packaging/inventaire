<?php

namespace App\Controller;

use App\Entity\Imprimante;
use App\Form\ImprimanteFormType;
use App\Repository\ImprimanteRepository;
use App\Service\ExcelExportService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/gestion/imprimantes', name: 'admin.imprimante.')]
class ImprimanteController extends AbstractController
{
    private $menu_active = "imprimante";

    /**
     * @param ImprimanteRepository $imprimanteRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Permet d'afficher la liste des imprimantes
     */
    #[Route(name: 'show')]
    public function index(ImprimanteRepository $imprimanteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $recherche = $request->query->get('par');
        $search = $request->query->get('q');

        if ($recherche && $search)
        {
            $imprimantesRepo = $imprimanteRepository->findByCritere($recherche, $search);
        }
        else
        {
            $imprimantesRepo = $imprimanteRepository->findBy([], ['id' => 'DESC']);
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $imprimantes = $paginator->paginate(
            $imprimantesRepo,
            $page,
            $limit
        );

        $total = $imprimantes->getTotalItemCount();

        return $this->render('imprimante/show.html.twig', [
            'imprimantes' => $imprimantes,
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param ExcelExportService $excelExportService
     * @param ImprimanteRepository $repository
     * @return Response
     * Permet d'exporter les données des imprimantes au format Excel
     */
    #[Route('/exporter', name: 'export')]
    public function exportDataToExcel(ExcelExportService $excelExportService, ImprimanteRepository $repository): Response
    {
        $imprimantes = $repository->findAll();

        if(count($imprimantes) !== 0)
        {
            $headers = ['ID', 'Nom', 'Marque', 'Modèle', 'IP', 'Numéro de série', 'Emplacement', 'Site', 'Fournisseur', 'État', 'Contrat', 'Date d\'installation', 'Date d\'achat', 'Date de garantie', 'Commentaire'];
            $data = [];

            foreach($imprimantes as $imprimante)
            {
                $data[] = [
                    $imprimante->getId(),
                    $imprimante->getNom(),
                    $imprimante->getMarque() ?? 'N/A',
                    $imprimante->getModele() ?? 'N/A',
                    $imprimante->getIp() ?? 'N/A',
                    $imprimante->getNumeroSerie() ?? 'N/A',
                    $imprimante->getEmplacement() !== null ? $imprimante->getEmplacement()->getNom() : 'N/A',
                    $imprimante->getEntreprise() !== null ? $imprimante->getEntreprise()->getNom() : 'N/A',
                    $imprimante->getFournisseur() !== null ? $imprimante->getFournisseur()->getNom() : 'N/A',
                    $imprimante->getEtat()->getNom(),
                    $imprimante->isContrat() == 0 ? 'Non' : 'Oui',
                    $imprimante->getDateInstallation() ?? 'N/A',
                    $imprimante->getDateAchat() ?? 'N/A',
                    $imprimante->getDateGarantie() ?? 'N/A',
                    $imprimante->getCommentaire() ?? 'N/A',
                ];
            }

            // Chemin où sauvegarder le fichier Excel
            $filePath = $this->getParameter('kernel.project_dir') . '/var/export_data_inventaire_imprimantes.xlsx';

            // Utilise le service pour exporter les données
            $excelExportService->exportToExcel($headers, $data, $filePath);

            // Télécharge le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inventaire_imprimantes.xlsx');
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
            $this->addFlash('danger', "Vous ne pouvez pas exporter les données car aucune imprimante n'a été trouvé !");

            return $this->redirectToRoute('admin.imprimante.show');
        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * Permet de créer une imprimante
     */
    #[Route('/creer', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $imprimante = new Imprimante();

        $imprimanteForm = $this->createForm(ImprimanteFormType::class, $imprimante);

        $imprimanteForm->handleRequest($request);

        if($imprimanteForm->isSubmitted() && $imprimanteForm->isValid())
        {
            $slugger = new AsciiSlugger();
            $nom = $imprimanteForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $imprimante->setSlug($slug);

            $entityManager->persist($imprimante);
            $entityManager->flush();

            $this->addFlash('success', "L'imprimante a été créée.");

            return $this->redirectToRoute('admin.imprimante.show');
        }

        return $this->render('imprimante/create.html.twig', [
            'imprimanteForm' => $imprimanteForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    /**
     * @param int $id
     * @param ImprimanteRepository $imprimanteRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     * Permet de rediriger vers le slug de l'imprimante
     */
    #[Route('/{id}', name: 'redirectToSlug', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function redirectToSlug(int $id, ImprimanteRepository $imprimanteRepository, UrlGeneratorInterface $urlGenerator): Response
    {
        $imprimante = $imprimanteRepository->find($id);
        if (!$imprimante) {
            throw $this->createNotFoundException();
        }

        $url = $urlGenerator->generate('admin.imprimante.edit', ['slug' => $imprimante->getSlug()]);
        return $this->redirect($url);
    }

    /**
     * @param Imprimante $imprimante
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @param NotificationService $notificationService
     * @return Response
     * Permet de modifier une imprimante
     */
    #[Route('/{slug}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Imprimante $imprimante, EntityManagerInterface $entityManager, Request $request, UrlGeneratorInterface $urlGenerator, NotificationService $notificationService): Response
    {
        $currentUrl = $urlGenerator->generate('admin.imprimante.redirectToSlug', ['slug' => $imprimante->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        $writer = new PngWriter();
        $qrCode = QrCode::create($currentUrl)
            ->setEncoding(new Encoding('UTF-8'))
            ->setSize(150)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $label = Label::create('')->setFont(new NotoSans(12));

        $qrCodes = $writer->write(
            $qrCode,
            null,
            $label->setText($imprimante->getNom())
        )->getDataUri();

        $imprimanteForm = $this->createForm(ImprimanteFormType::class, $imprimante);

        $imprimanteForm->handleRequest($request);

        if($imprimanteForm->isSubmitted() && $imprimanteForm->isValid())
        {
            $notificationService->deleteNotification("imprimante", $imprimante->getId());

            $slugger = new AsciiSlugger();
            $nom = $imprimanteForm->get('nom')->getData();
            $slug = strtolower($slugger->slug($nom));

            $imprimante->setSlug($slug);

            $entityManager->persist($imprimante);
            $entityManager->flush();

            $this->addFlash('success', "L'imprimante a été modifiée.");

            return $this->redirectToRoute('admin.imprimante.show');
        }

        return $this->render('imprimante/edit.html.twig', [
            'imprimanteForm' => $imprimanteForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    /**
     * @param Imprimante $imprimante
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param NotificationService $notificationService
     * @return Response
     * Permet de supprimer une imprimante
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Imprimante $imprimante, EntityManagerInterface $entityManager, Request $request, NotificationService $notificationService): Response
    {
        if($this->isCsrfTokenValid('delete'.$imprimante->getId(), $request->get('_token')))
        {
            $notificationService->deleteNotification("imprimante", $imprimante->getId());

            $entityManager->remove($imprimante);
            $entityManager->flush();

            $this->addFlash('success', "L'imprimante a été supprimée.");
        }

        return $this->redirectToRoute('admin.imprimante.show');
    }
}
