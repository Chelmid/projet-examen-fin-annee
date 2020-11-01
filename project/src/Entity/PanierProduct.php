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
     * @ORM\ManyToOne(targetEntity=Personnalisation::class, inversedBy="panierProduct",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $personnalisation;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="panierProduct")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Panier::class, inversedBy="panier",cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $panier;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $colorAndImage;

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getColorAndImage(): ?string
    {
        return $this->colorAndImage;
    }

    public function setColorAndImage(string $colorAndImage): self
    {
        $this->colorAndImage = $colorAndImage;

        return $this;
    }
}
