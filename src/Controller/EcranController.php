<?php

namespace App\Controller;

use App\Entity\Ecran;
use App\Form\EcranFormType;
use App\Repository\EcranRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/admin/ecrans', name: 'admin.ecran.')]
class EcranController extends AbstractController
{
    private $repository;
    private $menu_active = "ecran";

    #[Route('', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $ecrans = $registry->getManager()->getRepository(Ecran::class)->createQueryBuilder('e')
            ->select('e.id, e.nom, e.marque, e.modele, e.numero_serie, e.date_installation, e.date_achat, e.date_garantie, e.commentaire, utilisateur.nom as utilisateur_nom, utilisateur.prenom as utilisateur_prenom, emplacement.nom as emplacement_nom, etat.nom as etat_nom, fournisseur.nom as fournisseur_nom, entreprise.nom as entreprise_nom')
            ->leftJoin('e.utilisateur', 'utilisateur')
            ->leftJoin('e.emplacement', 'emplacement')
            ->leftJoin('e.fournisseur', 'fournisseur')
            ->leftJoin('e.entreprise', 'entreprise')
            ->leftJoin('e.etat', 'etat')
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('ecran/show.html.twig', [
            'ecrans' => $ecrans,
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $ecran = new Ecran();

        $ecranForm = $this->createForm(EcranFormType::class, $ecran);

        $ecranForm->handleRequest($request);

        if($ecranForm->isSubmitted() && $ecranForm->isValid())
        {
            $entityManager->persist($ecran);
            $entityManager->flush();

            $this->addFlash('success', "L'écran a bien été ajouté !");

            return $this->redirectToRoute('admin.ecran.show');
        }

        return $this->render('ecran/add.html.twig', [
            'ecranForm' => $ecranForm->createView(),
            'menu_active' => $this->menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Ecran $ecran, EntityManagerInterface $entityManager, Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $currentUrl = $urlGenerator->generate(
            $request->attributes->get('_route'),
            $request->attributes->get('_route_params'),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $writer = new PngWriter();
        $qrCode = QrCode::create($currentUrl)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(150)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $label = Label::create('')->setFont(new NotoSans(12));

        $qrCodes = $writer->write(
            $qrCode,
            null,
            $label->setText($ecran->getNom())
        )->getDataUri();

        $ecranForm = $this->createForm(EcranFormType::class, $ecran);

        $ecranForm->handleRequest($request);

        if($ecranForm->isSubmitted() && $ecranForm->isValid())
        {
            $entityManager->persist($ecran);
            $entityManager->flush();

            $this->addFlash('success', "L'écran a bien été modifié !");

            return $this->redirectToRoute('admin.ecran.show');
        }

        return $this->render('ecran/edit.html.twig', [
            'ecranForm' => $ecranForm->createView(),
            'menu_active' => $this->menu_active,
            'qrCodes' => $qrCodes,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Ecran $ecran, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$ecran->getId(), $request->get('_token')))
        {
            $entityManager->remove($ecran);
            $entityManager->flush();

            $this->addFlash('success', "L'écran a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.ecran.show');
    }
}
