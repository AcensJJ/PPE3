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
     * @ORM\OneToMany(targetEntity="App\Entity\ModeLivraison", mappedBy="yes")
     */
    private $mode;

    public function __construct()
    {
        $this->mode = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|ModeLivraison[]
     */
    public function getMode(): Collection
    {
        return $this->mode;
    }

    public function addMode(ModeLivraison $mode): self
    {
        if (!$this->mode->contains($mode)) {
            $this->mode[] = $mode;
            $mode->setYes($this);
        }

        return $this;
    }

    public function removeMode(ModeLivraison $mode): self
    {
        if ($this->mode->contains($mode)) {
            $this->mode->removeElement($mode);
            // set the owning side to null (unless already changed)
            if ($mode->getYes() === $this) {
                $mode->setYes(null);
            }
        }

        return $this;
    }
}
