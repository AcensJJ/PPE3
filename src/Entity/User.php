<?php
// src/Entity/User.php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
/**
* @ORM\Entity
* @ORM\Table(name="fos_user")
*/
class User extends BaseUser
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Panier", mappedBy="user", cascade={"persist", "remove"})
     */
    private $panier;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\IdentityUser", mappedBy="user", cascade={"persist", "remove"})
     */
    private $identityUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LivraisonUser", mappedBy="user")
     */
    private $livraisonUsers;

    public function __construct()
    {
    parent::__construct();
    $this->paniers = new ArrayCollection();
    $this->livraisonUsers = new ArrayCollection();
    // your own logic
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(Panier $panier): self
    {
        $this->panier = $panier;

        // set the owning side of the relation if necessary
        if ($this !== $panier->getUser()) {
            $panier->setUser($this);
        }

        return $this;
    }

    public function getIdentityUser(): ?IdentityUser
    {
        return $this->identityUser;
    }

    public function setIdentityUser(?IdentityUser $identityUser): self
    {
        $this->identityUser = $identityUser;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $identityUser === null ? null : $this;
        if ($newUser !== $identityUser->getUser()) {
            $identityUser->setUser($newUser);
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
            $livraisonUser->setUser($this);
        }

        return $this;
    }

    public function removeLivraisonUser(LivraisonUser $livraisonUser): self
    {
        if ($this->livraisonUsers->contains($livraisonUser)) {
            $this->livraisonUsers->removeElement($livraisonUser);
            // set the owning side to null (unless already changed)
            if ($livraisonUser->getUser() === $this) {
                $livraisonUser->setUser(null);
            }
        }

        return $this;
    }
}