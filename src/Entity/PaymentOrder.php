<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentOrderRepository")
 */
class PaymentOrder
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
    private $paymentId;

    /**
     * @ORM\Column(type="text")
     */
    private $status;

    /**
     * @ORM\Column(type="text")
     */
    private $amount;

    /**
     * @ORM\Column(type="text")
     */
    private $currency;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $payerPaypalEmail;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CommandeOrder", mappedBy="paymentOrder", cascade={"persist", "remove"})
     */
    private $commandeOrder;

    public function __toString() {
        return (string) "Voir les informations du paiement";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    public function setPaymentId(string $paymentId): self
    {
        $this->paymentId = $paymentId;
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

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }
    
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;
        return $this;
    }

    public function getPayerPaypalEmail(): ?string
    {
        return $this->payerPaypalEmail;
    }

    public function setPayerPaypalEmail(string $payerPaypalEmail): self
    {
        $this->payerPaypalEmail = $payerPaypalEmail;
        return $this;
    }

    public function getCommandeOrder(): ?CommandeOrder
    {
        return $this->commandeOrder;
    }
    
    public function setCommandeOrder(CommandeOrder $commandeOrder): self
    {
        $this->commandeOrder = $commandeOrder;
        // set the owning side of the relation if necessary
        if ($this !== $commandeOrder->getPaymentOrder()) {
            $commandeOrder->setPaymentOrder($this);
        }
        return $this;
    }
}