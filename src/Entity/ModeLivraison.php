<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModeLivraisonRepository")
 */
class ModeLivraison
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
    private $mode;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LivraisonOrder", mappedBy="modeLivraison")
     */
    private $livraisonOrders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LivraisonUser", mappedBy="modeLivraison")
     */
    private $livraisonUsers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
        $this->livraisonOrders = new ArrayCollection();
        $this->livraisonUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection|LivraisonOrder[]
     */
    public function getLivraisonOrders(): Collection
    {
        return $this->livraisonOrders;
    }

    public function addLivraisonOrder(LivraisonOrder $livraisonOrder): self
    {
        if (!$this->livraisonOrders->contains($livraisonOrder)) {
            $this->livraisonOrders[] = $livraisonOrder;
            $livraisonOrder->setModeLivraison($this);
        }

        return $this;
    }

    public function removeLivraisonOrder(LivraisonOrder $livraisonOrder): self
    {
        if ($this->livraisonOrders->contains($livraisonOrder)) {
            $this->livraisonOrders->removeElement($livraisonOrder);
            // set the owning side to null (unless already changed)
            if ($livraisonOrder->getModeLivraison() === $this) {
                $livraisonOrder->setModeLivraison(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LivraisonUser[]
     */
    public function getLivraisonUsers(): Collection
    {
        return $this->livraisonUsers;
    }

    public function addLivraisonUser(LivraisonUser $livraisonUser): self
    {
        if (!$this->livraisonUsers->contains($livraisonUser)) {
            $this->livraisonUsers[] = $livraisonUser;
            $livraisonUser->setModeLivraison($this);
        }

        return $this;
    }

    public function removeLivraisonUser(LivraisonUser $livraisonUser): self
    {
        if ($this->livraisonUsers->contains($livraisonUser)) {
            $this->livraisonUsers->removeElement($livraisonUser);
            // set the owning side to null (unless already changed)
            if ($livraisonUser->getModeLivraison() === $this) {
                $livraisonUser->setModeLivraison(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
