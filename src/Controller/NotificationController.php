<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestion/notifications', name: 'admin.notification.')]
class NotificationController extends AbstractController
{
    /**
     * @return JsonResponse
     * Permet de marquer toutes les notifications comme lues
     */
    #[Route('/lire', name: 'read', methods: ['GET'])]
    public function index(NotificationRepository $notificationRepository, EntityManagerInterface $entityManager): JsonResponse
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