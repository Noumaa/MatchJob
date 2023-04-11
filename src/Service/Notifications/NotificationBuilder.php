<?php

namespace App\Service\Notifications;

use App\Entity\Application;
use Doctrine\Persistence\ManagerRegistry;

class NotificationBuilder {

    private ManagerRegistry $doctrine;

    private Notification $type;
    private ?string $label;
    private ?string $content;
    private ?Application $demand;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return Notification
     */
    public function getType(): Notification
    {
        return $this->type;
    }

    /**
     * @param Notification $type
     */
    public function setType(Notification $type): NotificationBuilder
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label): NotificationBuilder
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): NotificationBuilder
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return Application|null
     */
    public function getDemand(): ?Application
    {
        return $this->demand;
    }

    /**
     * @param Application|null $demand
     */
    public function setDemand(?Application $demand): NotificationBuilder
    {
        $this->demand = $demand;
        return $this;
    }

    public function sendTo(array $users): void
    {
        foreach($users as $user) {
            $notification = new \App\Entity\Notification();

            $notification->setUser($user);

            $notification->setType($this->type->value);

            if (isset($this->label)) $notification->setLabel($this->label);
            if (isset($this->content)) $notification->setContent($this->content);

            if (isset($this->demand)) $notification->setDemand($this->demand);

            $this->doctrine->getManager()->persist($notification);
        }
        $this->doctrine->getManager()->flush();
    }
}