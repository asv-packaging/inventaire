<?php

namespace App\EventSubscriber;

use App\Entity\Ecran;
use App\Entity\Imprimante;
use App\Entity\Onduleur;
use App\Entity\PcFixe;
use App\Entity\PcPortable;
use App\Entity\Serveur;
use App\Entity\Tablette;
use App\Entity\TelephoneFixe;
use App\Entity\TelephonePortable;
use App\Service\NotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class KernelRequestSubscriber implements EventSubscriberInterface
{
    private $notificationService;
    private $twig;
    private $security;

    public function __construct(NotificationService $notificationService, Environment $twig, Security $security)
    {
        $this->notificationService = $notificationService;
        $this->twig = $twig;
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if($this->security->getUser() !== null)
        {
            $this->notificationService->sendNotification(Ecran::class, "ecran");
            $this->notificationService->sendNotification(Imprimante::class, "imprimante");
            $this->notificationService->sendNotification(Onduleur::class, "onduleur");
            $this->notificationService->sendNotification(PcFixe::class, "pc_fixe");
            $this->notificationService->sendNotification(PcPortable::class, "pc_portable");
            $this->notificationService->sendNotification(Serveur::class, "serveur");
            $this->notificationService->sendNotification(Tablette::class, "tablette");
            $this->notificationService->sendNotification(TelephoneFixe::class, "telephone_fixe");
            $this->notificationService->sendNotification(TelephonePortable::class, "telephone_portable");

            $this->twig->addGlobal('notifications', $this->notificationService->getNotifications());
            $this->twig->addGlobal('notificationsAll', $this->notificationService->getNotificationsAll());
            $this->twig->addGlobal('notificationsRead', $this->notificationService->getNotificationsRead());
            $this->twig->addGlobal('notificationsNotRead', $this->notificationService->getNotificationsNotRead());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}