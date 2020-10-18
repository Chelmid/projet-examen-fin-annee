<?php

namespace App\Entity;

use App\Repository\PersonnalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonnalisationRepository::class)
 */
class Personnalisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=PanierProduct::class, mappedBy="personnalisation")
     */
    private $panierProduct;

    public function __construct()
    {
        $this->panierProduct = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|PanierProduct[]
     */
    public function getPanierProduct(): Collection
    {
        return $this->panierProduct;
    }

    public function addPanierProduct(PanierProduct $panierProduct): self
    {
        if (!$this->panierProduct->contains($panierProduct)) {
            $this->panierProduct[] = $panierProduct;
            $panierProduct->setPersonnalisation($this);
        }

        return $this;
    }

    public function removePanierProduct(PanierProduct $panierProduct): self
    {
        if ($this->panierProduct->contains($panierProduct)) {
            $this->panierProduct->removeElement($panierProduct);
            // set the owning side to null (unless already changed)
            if ($panierProduct->getPersonnalisation() === $this) {
                $panierProduct->setPersonnalisation(null);
            }
        }

        return $this;
    }
}
