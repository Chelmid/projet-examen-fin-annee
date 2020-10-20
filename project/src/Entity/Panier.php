<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PanierRepository::class)
 */
class Panier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=PanierProduct::class, mappedBy="panier")
     */
    private $panierProduct;

    /**
     * @ORM\OneToMany(targetEntity=PanierProduct::class, mappedBy="panier", cascade={"persist", "remove"})
     */
    private $panier;

    public function __construct()
    {
        $this->panierProduct = new ArrayCollection();
        $this->panier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|PanierProduct[]
     */
    public function getPanier(): Collection
    {
        return $this->panier;
    }

    public function addPanier(PanierProduct $panier): self
    {
        if (!$this->panier->contains($panier)) {
            $this->panier[] = $panier;
            $panier->setPanier($this);
        }

        return $this;
    }

    public function removePanier(PanierProduct $panier): self
    {
        if ($this->panier->contains($panier)) {
            $this->panier->removeElement($panier);
            // set the owning side to null (unless already changed)
            if ($panier->getPanier() === $this) {
                $panier->setPanier(null);
            }
        }

        return $this;
    }
}
