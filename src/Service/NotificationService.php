<?php
namespace App\Service;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $materielClass
     * @param $materielNom
     * @return void
     * Envoie une notification lorsque la date de fin de garantie d'un matériel arrive bientôt à expiration ou est arrivée à expiration
     */
    public function sendNotification($materielClass, $materielNom): void
    {
        $currentDate = new \DateTimeImmutable('now');

        $date1moisAvant = $currentDate->modify('-1 month')->format('d/m/Y');
        $dateActuelle = $currentDate->format('d/m/Y');

        $materiels = $this->entityManager->getRepository($materielClass)
            ->createQueryBuilder('m')
            ->where('m.date_garantie IS NOT NULL')
            ->getQuery()
            ->getResult();

        foreach ($materiels as $materiel)
        {
            $identifiantBientot = "bientot_fin_garantie_".$materielNom."_".$materiel->getId();
            $identifiantFin = "fin_garantie_".$materielNom."_".$materiel->getId();

            $existingNotificationBientot = $this->entityManager->getRepository(Notification::class)
                ->findOneBy([
                    'identifiant' => $identifiantBientot,
                ]);

            $existingNotificationFin = $this->entityManager->getRepository(Notification::class)
                ->findOneBy([
                    'identifiant' => $identifiantFin,
                ]);

            $dateFinGarantie = \DateTimeImmutable::createFromFormat('d/m/Y', $materiel->getDateGarantie());

            // Permet de calculer la différence entre la date actuelle et la date de fin de garantie
            $interval = $currentDate->diff($dateFinGarantie);

            if($materielNom == "ecran")
            {
                $materielMessage = "de l'écran";
            }
            elseif($materielNom == "imprimante")
            {
                $materielMessage = "de l'imprimante";
            }
            elseif($materielNom == "onduleur")
            {
                $materielMessage = "de l'onduleur";
            }
            elseif($materielNom == "pc_fixe")
            {
                $materielMessage = "du PC Fixe";
            }
            elseif($materielNom == "pc_portable")
            {
                $materielMessage = "du PC Portable";
            }
            elseif($materielNom == "serveur")
            {
                $materielMessage = "du serveur";
            }
            elseif($materielNom == "tablette")
            {
                $materielMessage = "de la tablette";
            }
            elseif($materielNom == "telephone_fixe")
            {
                $materielMessage = "du téléphone fixe";
            }
            elseif($materielNom == "telephone_portable")
            {
                $materielMessage = "du téléphone portable";
            }
            else
            {
                $materielMessage = "N/A";
            }

            if($materielNom == "telephone_fixe" || $materielNom == "telephone_portable")
            {
                $materielInfo = $materiel->getLigne();
            }
            else
            {
                $materielInfo = $materiel->getNom();
            }

            if ($currentDate >= $dateFinGarantie && !$existingNotificationFin)
            {
                // Envoie une notification lorsque le matériel est arrivé à expiration
                $notificationFin = new Notification();
                $notificationFin->setMessage("La date de <strong>fin de garantie</strong> ".$materielMessage." <strong>".$materielInfo."</strong> est <strong>arrivée à expiration</strong>.");
                $notificationFin->setIcon("fas fa-triangle-exclamation text-danger");
                $notificationFin->setIsRead(false);
                $notificationFin->setIdentifiant($identifiantFin);
                $notificationFin->setCreatedAt(new \DateTimeImmutable('now'));

                $this->entityManager->persist($notificationFin);
            }
            elseif ($interval->days <= 30 && !$existingNotificationBientot)
            {
                // Envoie une notification lorsque le matériel arrive bientôt à expiration (sur un interval de 30 jours ou moins)
                $notificationBientot = new Notification();
                $notificationBientot->setMessage("La date de <strong>fin de garantie</strong> ".$materielMessage." <strong>".$materielInfo."</strong> arrive <strong>bientôt à expiration</strong>.");
                $notificationBientot->setIcon("fas fa-triangle-exclamation text-warning");
                $notificationBientot->setIsRead(false);
                $notificationBientot->setIdentifiant($identifiantBientot);
                $notificationBientot->setCreatedAt(new \DateTimeImmutable('now'));

                $this->entityManager->persist($notificationBientot);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @return Notification[]|array|object[]
     * Récupère toutes les notifications non lues
     */
    public function getNotifications(): array
    {
        $notifications = $this->entityManager->getRepository(Notification::class)->findBy(
            ['isRead' => false],
            ['createdAt' => 'DESC']
        );

        return $notifications;
    }

    /**
     * @return Notification[]|array|object[]
     * Récupère toutes les notifications
     */
    public function getNotificationsAll(): array
    {
        $notifications = $this->entityManager->getRepository(Notification::class)->findBy(
            [],
            ['createdAt' => 'DESC']
        );

        return $notifications;
    }

    /**
     * @return Notification[]|array|object[]
     * Récupère toutes les notifications lues
     */
    public function getNotificationsRead(): array
    {
        $notifications = $this->entityManager->getRepository(Notification::class)->findBy(
            ['isRead' => true],
            ['createdAt' => 'DESC']
        );

        return $notifications;
    }

    /**
     * @return Notification[]|array|object[]
     * Récupère toutes les notifications non lues
     */
    public function getNotificationsNotRead(): array
    {
        $notifications = $this->entityManager->getRepository(Notification::class)->findBy(
            ['isRead' => false],
            ['createdAt' => 'DESC']
        );

        return $notifications;
    }

    /**
     * @param $materielNom
     * @param $materielId
     * @return void
     * Supprime les notifications d'un matériel
     */
    public function deleteNotification($materielNom, $materielId): void
    {
        $identifiantBientot = "bientot_fin_garantie_".$materielNom."_".$materielId;
        $identifiantFin = "fin_garantie_".$materielNom."_".$materielId;

        $notificationBientot = $this->entityManager->getRepository(Notification::class)
            ->findOneBy([
                'identifiant' => $identifiantBientot,
            ]);

        $notificationFin = $this->entityManager->getRepository(Notification::class)
            ->findOneBy([
                'identifiant' => $identifiantFin,
            ]);

        if ($notificationBientot)
        {
            $this->entityManager->remove($notificationBientot);
        }

        if ($notificationFin)
        {
            $this->entityManager->remove($notificationFin);
        }

        $this->entityManager->flush();
    }
}