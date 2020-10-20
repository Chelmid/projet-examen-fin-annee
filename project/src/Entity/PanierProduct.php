<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PanierProductRepository::class)
 */
class PanierProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Personnalisation::class, inversedBy="panierProduct")
     * @ORM\JoinColumn(nullable=true)
     */
    private $personnalisation;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="panierProduct")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Panier::class, inversedBy="panier", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $panier;

    public function __construct()
    {
        $this->panier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonnalisation(): ?personnalisation
    {
        return $this->personnalisation;
    }

    public function setPersonnalisation(?personnalisation $personnalisation): self
    {
        $this->personnalisation = $personnalisation;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }
}
