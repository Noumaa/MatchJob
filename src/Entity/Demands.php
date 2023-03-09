<?php

namespace App\Entity;

use App\Repository\DemandsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: DemandsRepository::class)]
#[ORM\HasLifecycleCallbacks]
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

    #[ORM\OneToMany(mappedBy: 'Demand', targetEntity: DemandStatusChange::class, orphanRemoval: true)]
    private Collection $demandStatusChanges;


    public function __construct()
    {
        $this->demandStatusChanges = new ArrayCollection();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateDates(): void
    {
        $this->setDateUpdate(new \DateTime('now'));
        if ($this->getDateAdd() === null) {
            $this->setDateAdd(new \DateTime('now'));
        }
    }

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

    /**
     * @return Collection<int, DemandStatusChange>
     */
    public function getDemandStatusChanges(): Collection
    {
        return $this->demandStatusChanges;
    }

    public function addDemandStatusChange(DemandStatusChange $demandStatusChange): self
    {
        if (!$this->demandStatusChanges->contains($demandStatusChange)) {
            $this->demandStatusChanges->add($demandStatusChange);
            $demandStatusChange->setDemand($this);
        }

        return $this;
    }

    public function removeDemandStatusChange(DemandStatusChange $demandStatusChange): self
    {
        if ($this->demandStatusChanges->removeElement($demandStatusChange)) {
            // set the owning side to null (unless already changed)
            if ($demandStatusChange->getDemand() === $this) {
                $demandStatusChange->setDemand(null);
            }
        }

        return $this;
    }
}
