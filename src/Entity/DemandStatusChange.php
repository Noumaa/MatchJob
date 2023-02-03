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

    #[ORM\ManyToOne(inversedBy: 'demandStatusChanges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Demands $Demand = null;

    #[ORM\ManyToOne(inversedBy: 'demandStatusChanges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DemandStatus $DemandStatus = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_update = null;

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

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

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
