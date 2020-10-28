<?php

namespace App\Entity;

use App\Repository\PriceImpressionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PriceImpressionRepository::class)
 */
class PriceImpression
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=Personnalisation::class, mappedBy="priceImpression")
     */
    private $personnalisation;

    public function __construct()
    {
        $this->personnalisation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Personnalisation[]
     */
    public function getPersonnalisation(): Collection
    {
        return $this->personnalisation;
    }

    public function addPersonnalisation(Personnalisation $personnalisation): self
    {
        if (!$this->personnalisation->contains($personnalisation)) {
            $this->personnalisation[] = $personnalisation;
            $personnalisation->setPriceImpression($this);
        }

        return $this;
    }

    public function removePersonnalisation(Personnalisation $personnalisation): self
    {
        if ($this->personnalisation->removeElement($personnalisation)) {
            // set the owning side to null (unless already changed)
            if ($personnalisation->getPriceImpression() === $this) {
                $personnalisation->setPriceImpression(null);
            }
        }

        return $this;
    }
}
