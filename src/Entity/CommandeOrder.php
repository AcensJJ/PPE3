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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commandeOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\IdentityOrder", inversedBy="commandeOrder", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $identity;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\LivraisonOrder", inversedBy="commandeOrder", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $livraison;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PaymentOrder", inversedBy="commandeOrder", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentOrder;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Produit", inversedBy="commandeOrders")
     */
    private $produit;

    

    public function __construct()
    {
        $this->relation = new ArrayCollection();
        $this->article = new ArrayCollection();
        $this->produit = new ArrayCollection();
    }

    public function __toString() {
        return (string) "Commande";
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

    public function getModeLivraison(): ?ModeLivraison
    {
        return $this->modeLivraison;
    }

    public function setModeLivraison(?ModeLivraison $modeLivraison): self
    {
        $this->modeLivraison = $modeLivraison;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdentity(): ?IdentityOrder
    {
        return $this->identity;
    }

    public function setIdentity(IdentityOrder $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    public function getLivraison(): ?LivraisonOrder
    {
        return $this->livraison;
    }

    public function setLivraison(LivraisonOrder $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getPaymentOrder(): ?PaymentOrder
    {
        return $this->paymentOrder;
    }
    public function setPaymentOrder(PaymentOrder $paymentOrder): self
    {
        $this->paymentOrder = $paymentOrder;
        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit[] = $produit;
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produit->contains($produit)) {
            $this->produit->removeElement($produit);
        }

        return $this;
    }

    
    
}
