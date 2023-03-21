<?php

namespace App\Entity;

use App\Repository\DemandStatusChangeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandStatusChangeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class DemandStatusChange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'demandStatusChanges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Demands $Demand = null;

    #[ORM\ManyToOne(inversedBy: 'demandStatusChanges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DemandStatus $DemandStatus = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\PrePersist]
    public function prePersist()
    {
        if ($this->date == null) $this->date = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDemand(): ?Demands
    {
        return $this->Demand;
    }

    public function setDemand(?Demands $Demand): self
    {
        $this->Demand = $Demand;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDemandStatus(): ?DemandStatus
    {
        return $this->DemandStatus;
    }

    public function setDemandStatus(?DemandStatus $DemandStatus): self
    {
        $this->DemandStatus = $DemandStatus;

        return $this;
    }
}
