<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $SKU;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     */
    private $quantity;

    /**
     * @ORM\OneToOne(targetEntity=ZoneDeMarquage::class, mappedBy="product_id", cascade={"persist", "remove"})
     */
    private $zoneDeMarquage;

    /**
     * @ORM\OneToMany(targetEntity=PanierProduct::class, mappedBy="product")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSKU(): ?string
    {
        return $this->SKU;
    }

    public function setSKU(string $SKU): self
    {
        $this->SKU = $SKU;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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

    public function getZoneDeMarquage(): ?ZoneDeMarquage
    {
        return $this->zoneDeMarquage;
    }

    public function setZoneDeMarquage(ZoneDeMarquage $zoneDeMarquage): self
    {
        $this->zoneDeMarquage = $zoneDeMarquage;

        // set the owning side of the relation if necessary
        if ($zoneDeMarquage->getProductId() !== $this) {
            $zoneDeMarquage->setProductId($this);
        }

        return $this;
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
            $panierProduct->setProduct($this);
        }

        return $this;
    }

    public function removePanierProduct(PanierProduct $panierProduct): self
    {
        if ($this->panierProduct->contains($panierProduct)) {
            $this->panierProduct->removeElement($panierProduct);
            // set the owning side to null (unless already changed)
            if ($panierProduct->getProduct() === $this) {
                $panierProduct->setProduct(null);
            }
        }

        return $this;
    }
}
