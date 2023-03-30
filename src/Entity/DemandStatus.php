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

    #[ORM\OneToMany(mappedBy: 'status', targetEntity: Demands::class)]
    private Collection $demands;

    public function __construct()
    {
        $this->demands = new ArrayCollection();
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
     * @return Collection<int, Demands>
     */
    public function getDemands(): Collection
    {
        return $this->demands;
    }

    public function addDemand(Demands $demand): self
    {
        if (!$this->demands->contains($demand)) {
            $this->demands->add($demand);
            $demand->setStatus($this);
        }

        return $this;
    }

    public function removeDemand(Demands $demand): self
    {
        if ($this->demands->removeElement($demand)) {
            // set the owning side to null (unless already changed)
            if ($demand->getStatus() === $this) {
                $demand->setStatus(null);
            }
        }

        return $this;
    }
}
