<?php

namespace App\Entity;

use App\Repository\DemandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandRepository::class)]
class Demand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note = null;

    #[ORM\ManyToOne(inversedBy: 'demands')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'demand', targetEntity: DemandStatusChange::class, orphanRemoval: true)]
    private Collection $demandStatusChanges;

    #[ORM\ManyToOne(inversedBy: 'demands')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offer $offer = null;

    public function __construct()
    {
        $this->demandStatusChanges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }
}
