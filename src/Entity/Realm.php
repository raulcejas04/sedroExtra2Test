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
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="realm")
     */
    private $solicitudes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keycloakRealmId;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="realm",cascade={"persist"})
     */
    private $users;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEliminacion;

    /**
     * @ORM\OneToMany(targetEntity=Grupo::class, mappedBy="realm",cascade={"persist"})
     */
    private $grupos;

    /**
     * @ORM\OneToMany(targetEntity=Role::class, mappedBy="realm")
     */
    private $roles;

    public function __toString()
    {
        return $this->getRealm();
    }    

    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->grupos = new ArrayCollection();
        $this->roles = new ArrayCollection();
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
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->setRealm($this);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->removeElement($role)) {
            // set the owning side to null (unless already changed)
            if ($role->getRealm() === $this) {
                $role->setRealm(null);
            }
        }

        return $this;
    }

}
