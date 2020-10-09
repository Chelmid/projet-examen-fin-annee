<?php

namespace App\Entity;

use App\Repository\ZoneDeMarquageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZoneDeMarquageRepository::class)
 */
class ZoneDeMarquage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $left_space;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $top_space;

    /**
     * @ORM\OneToOne(targetEntity=Product::class, inversedBy="zoneDeMarquage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $product_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getLeftSpace(): ?int
    {
        return $this->left_space;
    }

    public function setLeftSpace(?int $left_space): self
    {
        $this->left_space = $left_space;

        return $this;
    }

    public function getTopSpace(): ?int
    {
        return $this->top_space;
    }

    public function setTopSpace(?int $top_space): self
    {
        $this->top_space = $top_space;

        return $this;
    }

    public function getProductId(): ?Product
    {
        return $this->product_id;
    }

    public function setProductId(Product $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }
}
