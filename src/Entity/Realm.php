<?php

namespace App\Entity;

use App\Repository\RealmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RealmRepository::class)
 */
class Realm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $realm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idRealmKeycloak;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="realm")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="realm")
     */
    private $solicitudes;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->solicitudes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealm(): ?string
    {
        return $this->realm;
    }

    public function setRealm(string $realm): self
    {
        $this->realm = $realm;

        return $this;
    }

    public function getIdRealmKeycloak(): ?string
    {
        return $this->idRealmKeycloak;
    }

    public function setIdRealmKeycloak(?string $idRealmKeycloak): self
    {
        $this->idRealmKeycloak = $idRealmKeycloak;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setRealm($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getRealm() === $this) {
                $user->setRealm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Solicitud[]
     */
    public function getSolicitudes(): Collection
    {
        return $this->solicitudes;
    }

    public function addSolicitude(Solicitud $solicitude): self
    {
        if (!$this->solicitudes->contains($solicitude)) {
            $this->solicitudes[] = $solicitude;
            $solicitude->setRealm($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getRealm() === $this) {
                $solicitude->setRealm(null);
            }
        }

        return $this;
    }
}
