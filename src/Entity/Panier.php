<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PanierRepository")
 */
class Panier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="paniers")
     */
    private $IdProduit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="paniers")
     */
    private $IDUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $panStock;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->IdProduit;
    }

    public function setIdProduit(?Produit $IdProduit): self
    {
        $this->IdProduit = $IdProduit;

        return $this;
    }

    public function getIDUser(): ?User
    {
        return $this->IDUser;
    }

    public function setIDUser(?User $IDUser): self
    {
        $this->IDUser = $IDUser;

        return $this;
    }

    public function getPanStock(): ?int
    {
        return $this->panStock;
    }

    public function setPanStock(int $panStock): self
    {
        $this->panStock = $panStock;

        return $this;
    }
}
