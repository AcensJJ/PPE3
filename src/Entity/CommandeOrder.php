<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeOrderRepository")
 */
class CommandeOrder
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
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Produit", inversedBy="commande")
     */
    private $relation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ModeLivraison", inversedBy="commande")
     */
    private $modeLivraison;

    public function __construct()
    {
        $this->relation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    /**
     * @return Collection|Produit[]
     */
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(Produit $relation): self
    {
        if (!$this->relation->contains($relation)) {
            $this->relation[] = $relation;
        }

        return $this;
    }

    public function removeRelation(Produit $relation): self
    {
        if ($this->relation->contains($relation)) {
            $this->relation->removeElement($relation);
        }

        return $this;
    }

    public function getModeLivraison(): ?ModeLivraison
    {
        return $this->modeLivraison;
    }

    public function setModeLivraison(?ModeLivraison $modeLivraison): self
    {
        $this->modeLivraison = $modeLivraison;

        return $this;
    }
}
