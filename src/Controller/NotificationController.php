<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notifications', name: 'admin.notification.')]
class NotificationController extends AbstractController
{
    private $menu_active = null;

    #[Route( name: 'show')]
    public function show(NotificationRepository $notificationRepository): Response
    {
        $notifications = $notificationRepository->findBy([], [
            'createdAt' => 'DESC',
        ]);

        return $this->render('notification/show.html.twig', [
            'menu_active' => $this->menu_active,
            'notificationss' => $notifications,
        ]);
    }

    #[Route('/marquer-comme-lu', name: 'read_all', methods: ['GET'])]
    public function readAll (NotificationRepository $notificationRepository, EntityManagerInterface $entityManager): Response
    {
        $notifications = $notificationRepository->findBy([
            'isRead' => false,
        ]);

        foreach ($notifications as $notification)
        {
            $notification->setIsRead(true);
            $entityManager->persist($notification);
        }

        $entityManager->flush();

        return $this->redirectToRoute('admin.notification.show');
    }

    /**
     * @return JsonResponse
     * Permet de marquer toutes les notifications comme lues
     */
    #[Route('/lire', name: 'read', methods: ['GET'])]
    public function readAjax(NotificationRepository $notificationRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $notifications = $notificationRepository->findBy([
            'isRead' => false,
        ]);

        foreach ($notifications as $notification)
        {
            $notification->setIsRead(true);
            $entityManager->persist($notification);
        }

        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
        ]);
    }
}