<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LivraisonUserRepository")
 */
class LivraisonUser
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
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="livraisonUsers")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ModeLivraison", inversedBy="livraisonUsers")
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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

}
