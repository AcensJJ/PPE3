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
     * @ORM\ManyToOne(targetEntity="App\Entity\LivraisonOrder", inversedBy="modeLivraison")
     */
    private $livraison;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LivraisonUser", inversedBy="mode")
     */
    private $yes;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
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

    public function getLivraison(): ?LivraisonOrder
    {
        return $this->livraison;
    }

    public function setLivraison(?LivraisonOrder $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getYes(): ?LivraisonUser
    {
        return $this->yes;
    }

    public function setYes(?LivraisonUser $yes): self
    {
        $this->yes = $yes;

        return $this;
    }
}
