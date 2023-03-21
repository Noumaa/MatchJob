<?php

namespace App\Entity;

use App\Repository\DemandStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandStatusRepository::class)]
class DemandStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'ApplicationStatus', targetEntity: DemandStatusChange::class, orphanRemoval: true)]
    private Collection $demandStatusChanges;

    public function __construct()
    {
        $this->demandStatusChanges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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
            $demandStatusChange->setDemandStatus($this);
        }

        return $this;
    }

    public function removeDemandStatusChange(DemandStatusChange $demandStatusChange): self
    {
        if ($this->demandStatusChanges->removeElement($demandStatusChange)) {
            // set the owning side to null (unless already changed)
            if ($demandStatusChange->getDemandStatus() === $this) {
                $demandStatusChange->setDemandStatus(null);
            }
        }

        return $this;
    }
}
