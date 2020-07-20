<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $username;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $kredi;

    /**
     * @ORM\OneToMany(targetEntity=Sales::class, mappedBy="user", orphanRemoval=true)
     */
    private $salesUser;

    public function __construct()
    {
        $this->salesUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getKredi(): ?float
    {
        return $this->kredi;
    }

    public function setKredi(?float $kredi): self
    {
        $this->kredi = $kredi;

        return $this;
    }

    /**
     * @return Collection|Sales[]
     */
    public function getSalesUser(): Collection
    {
        return $this->salesUser;
    }

    public function addSalesUser(Sales $salesUser): self
    {
        if (!$this->salesUser->contains($salesUser)) {
            $this->salesUser[] = $salesUser;
            $salesUser->setUser($this);
        }

        return $this;
    }

    public function removeSalesUser(Sales $salesUser): self
    {
        if ($this->salesUser->contains($salesUser)) {
            $this->salesUser->removeElement($salesUser);
            // set the owning side to null (unless already changed)
            if ($salesUser->getUser() === $this) {
                $salesUser->setUser(null);
            }
        }

        return $this;
    }
}
