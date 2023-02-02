<?php

namespace App\Entity;

use App\Repository\DemandsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: DemandsRepository::class)]
#[UniqueEntity(fields: ['Individual', 'Offer'], message: 'Vous avez déjà déposé votre candidature !')]
class Demands
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'demands')]
    private ?User $Individual = null;

    #[ORM\ManyToOne(inversedBy: 'demands')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offer $Offer = null;


    #[ORM\Column(type:"datetime", name:"date_add")]
    private ?\DateTimeInterface $date_add = null;

    
    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"], name:"date_update")]
    
    private ?\DateTimeInterface $date_update = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndividual(): ?User
    {
        return $this->Individual;
    }

    public function setIndividual(?User $Individual): self
    {
        $this->Individual = $Individual;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->Offer;
    }

    public function setOffer(?Offer $Offer): self
    {
        $this->Offer = $Offer;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    #[ORM\PrePersist]
    public function setDateAdd(\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    #[ORM\PreUpdate]
    public function setDateUpdate(\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

        return $this;
    }
}
