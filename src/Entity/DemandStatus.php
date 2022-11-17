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

    #[ORM\Column(length: 32)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'demandStatus', targetEntity: DemandStatusChange::class, orphanRemoval: true)]
    private Collection $demandStatusChanges;

    public function __construct()
    {
        $this->demandStatusChanges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
