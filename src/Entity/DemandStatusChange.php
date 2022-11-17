<?php

namespace App\Entity;

use App\Repository\DemandStatusChangeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandStatusChangeRepository::class)]
class DemandStatusChange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\ManyToOne(inversedBy: 'demandStatusChanges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Demand $demand = null;

    #[ORM\ManyToOne(inversedBy: 'demandStatusChanges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DemandStatus $demandStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getDemand(): ?Demand
    {
        return $this->demand;
    }

    public function setDemand(?Demand $demand): self
    {
        $this->demand = $demand;

        return $this;
    }

    public function getDemandStatus(): ?DemandStatus
    {
        return $this->demandStatus;
    }

    public function setDemandStatus(?DemandStatus $demandStatus): self
    {
        $this->demandStatus = $demandStatus;

        return $this;
    }
}
