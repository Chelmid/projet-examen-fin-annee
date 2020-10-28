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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $topPosition;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $leftPosition;

    /**
     * @ORM\Column(type="integer")
     */
    private $width;

    /**
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=4294967292)
     */
    private $datauri;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $impression;

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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getTopPosition(): ?string
    {
        return $this->topPosition;
    }

    public function setTopPosition(string $topPosition): self
    {
        $this->topPosition = $topPosition;

        return $this;
    }

    public function getLeftPosition(): ?string
    {
        return $this->leftPosition;
    }

    public function setLeftPosition(string $leftPosition): self
    {
        $this->leftPosition = $leftPosition;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getDatauri(): ?string
    {
        return $this->datauri;
    }

    public function setDatauri(string $datauri): self
    {
        $this->datauri = $datauri;

        return $this;
    }

    public function getImpression(): ?string
    {
        return $this->impression;
    }

    public function setImpression(string $impression): self
    {
        $this->impression = $impression;

        return $this;
    }
}
