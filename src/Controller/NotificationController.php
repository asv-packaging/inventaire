<?php

namespace App\Controller;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/notifications', name: 'admin.notification.')]
class NotificationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/lire', name: 'read', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $notifications = $this->entityManager->getRepository(Notification::class)->findBy([
            'isRead' => false,
        ]);

        foreach ($notifications as $notification)
        {
            $notification->setIsRead(true);
            $this->entityManager->persist($notification);
        }

        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
        ]);
    }
}