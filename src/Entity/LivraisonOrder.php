<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LivraisonOrderRepository")
 */
class LivraisonOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateLivraison;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEnvoie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ModeLivraison", mappedBy="livraison")
     */
    private $modeLivraison;

    public function __construct()
    {
        $this->modeLivraison = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getDateEnvoie(): ?\DateTimeInterface
    {
        return $this->dateEnvoie;
    }

    public function setDateEnvoie(?\DateTimeInterface $dateEnvoie): self
    {
        $this->dateEnvoie = $dateEnvoie;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection|ModeLivraison[]
     */
    public function getModeLivraison(): Collection
    {
        return $this->modeLivraison;
    }

    public function addModeLivraison(ModeLivraison $modeLivraison): self
    {
        if (!$this->modeLivraison->contains($modeLivraison)) {
            $this->modeLivraison[] = $modeLivraison;
            $modeLivraison->setLivraison($this);
        }

        return $this;
    }

    public function removeModeLivraison(ModeLivraison $modeLivraison): self
    {
        if ($this->modeLivraison->contains($modeLivraison)) {
            $this->modeLivraison->removeElement($modeLivraison);
            // set the owning side to null (unless already changed)
            if ($modeLivraison->getLivraison() === $this) {
                $modeLivraison->setLivraison(null);
            }
        }

        return $this;
    }
}
