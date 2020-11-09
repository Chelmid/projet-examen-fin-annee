<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Panier::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $panier;

    /**
     * @ORM\Column(type="float")
     */
    private $Total_price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_order;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $method_payement;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commande")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(Panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->Total_price;
    }

    public function setTotalPrice(string $Total_price): self
    {
        $this->Total_price = $Total_price;

        return $this;
    }

    public function getDateOrder(): ?\DateTimeInterface
    {
        return $this->date_order;
    }

    public function setDateOrder(\DateTimeInterface $date_order): self
    {
        $this->date_order = $date_order;

        return $this;
    }

    public function getMethodPayement(): ?string
    {
        return $this->method_payement;
    }

    public function setMethodPayement(string $method_payement): self
    {
        $this->method_payement = $method_payement;

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
}
