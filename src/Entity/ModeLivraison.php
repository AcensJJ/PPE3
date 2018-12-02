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
     * @ORM\OneToMany(targetEntity="App\Entity\CommandeOrder", mappedBy="modeLivraison")
     */
    private $commande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mode;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|CommandeOrder[]
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(CommandeOrder $commande): self
    {
        if (!$this->commande->contains($commande)) {
            $this->commande[] = $commande;
            $commande->setModeLivraison($this);
        }

        return $this;
    }

    public function removeCommande(CommandeOrder $commande): self
    {
        if ($this->commande->contains($commande)) {
            $this->commande->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getModeLivraison() === $this) {
                $commande->setModeLivraison(null);
            }
        }

        return $this;
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
}
