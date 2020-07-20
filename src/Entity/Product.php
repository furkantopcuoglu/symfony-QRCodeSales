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
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Sales::class, mappedBy="product", orphanRemoval=true)
     */
    private $salesProduct;

    public function __construct()
    {
        $this->salesProduct = new ArrayCollection();
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

    /**
     * @return Collection|Sales[]
     */
    public function getSalesProduct(): Collection
    {
        return $this->salesProduct;
    }

    public function addSalesProduct(Sales $salesProduct): self
    {
        if (!$this->salesProduct->contains($salesProduct)) {
            $this->salesProduct[] = $salesProduct;
            $salesProduct->setProduct($this);
        }

        return $this;
    }

    public function removeSalesProduct(Sales $salesProduct): self
    {
        if ($this->salesProduct->contains($salesProduct)) {
            $this->salesProduct->removeElement($salesProduct);
            // set the owning side to null (unless already changed)
            if ($salesProduct->getProduct() === $this) {
                $salesProduct->setProduct(null);
            }
        }

        return $this;
    }
}
