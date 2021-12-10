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
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="realm")
     */
    private $solicitudes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keycloakRealmId;

    /**
     * @ORM\OneToMany(targetEntity=UserRealm::class, mappedBy="realm")
     */
    private $userRealms;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEliminacion;

    /**
     * @ORM\OneToMany(targetEntity=Grupo::class, mappedBy="realm")
     */
    private $grupos;

    public function __toString()
    {
        return $this->getRealm();
    }    

    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
        $this->userRealms = new ArrayCollection();
        $this->grupos = new ArrayCollection();
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

    public function getKeycloakRealmId(): ?string
    {
        return $this->keycloakRealmId;
    }

    public function setKeycloakRealmId(string $keycloakRealmId): self
    {
        $this->keycloakRealmId = $keycloakRealmId;

        return $this;
    }

    /**
     * @return Collection|UserRealm[]
     */
    public function getUserRealms(): Collection
    {
        return $this->userRealms;
    }

    public function addUserRealm(UserRealm $userRealm): self
    {
        if (!$this->userRealms->contains($userRealm)) {
            $this->userRealms[] = $userRealm;
            $userRealm->setRealm($this);
        }

        return $this;
    }

    public function removeUserRealm(UserRealm $userRealm): self
    {
        if ($this->userRealms->removeElement($userRealm)) {
            // set the owning side to null (unless already changed)
            if ($userRealm->getRealm() === $this) {
                $userRealm->setRealm(null);
            }
        }

        return $this;
    }

    public function getFechaEliminacion(): ?\DateTimeInterface
    {
        return $this->fechaEliminacion;
    }

    public function setFechaEliminacion(?\DateTimeInterface $fechaEliminacion): self
    {
        $this->fechaEliminacion = $fechaEliminacion;

        return $this;
    }

    /**
     * @return Collection|Grupo[]
     */
    public function getGrupos(): Collection
    {
        return $this->grupos;
    }

    public function addGrupo(Grupo $grupo): self
    {
        if (!$this->grupos->contains($grupo)) {
            $this->grupos[] = $grupo;
            $grupo->setRealm($this);
        }

        return $this;
    }

    public function removeGrupo(Grupo $grupo): self
    {
        if ($this->grupos->removeElement($grupo)) {
            // set the owning side to null (unless already changed)
            if ($grupo->getRealm() === $this) {
                $grupo->setRealm(null);
            }
        }

        return $this;
    }

}
