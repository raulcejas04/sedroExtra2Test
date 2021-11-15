<?php

namespace App\Entity;

use App\Repository\TipoDispositivoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoDispositivoRepository::class)
 */
class TipoDispositivo
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
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Dispositivo::class, mappedBy="tipoDispositivo")
     */
    private $dispositivos;

    /**
     * @ORM\OneToMany(targetEntity=Grupo::class, mappedBy="tipoDispositivo")
     */
    private $grupos;

    /**
     * @ORM\OneToMany(targetEntity=Rol::class, mappedBy="tipoDispositivo")
     */
    private $roles;

    public function __construct()
    {
        $this->dispositivos = new ArrayCollection();
        $this->grupos = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Dispositivo[]
     */
    public function getDispositivos(): Collection
    {
        return $this->dispositivos;
    }

    public function addDispositivo(Dispositivo $dispositivo): self
    {
        if (!$this->dispositivos->contains($dispositivo)) {
            $this->dispositivos[] = $dispositivo;
            $dispositivo->setTipoDispositivo($this);
        }

        return $this;
    }

    public function removeDispositivo(Dispositivo $dispositivo): self
    {
        if ($this->dispositivos->removeElement($dispositivo)) {
            // set the owning side to null (unless already changed)
            if ($dispositivo->getTipoDispositivo() === $this) {
                $dispositivo->setTipoDispositivo(null);
            }
        }

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
            $grupo->setTipoDispositivo($this);
        }

        return $this;
    }

    public function removeGrupo(Grupo $grupo): self
    {
        if ($this->grupos->removeElement($grupo)) {
            // set the owning side to null (unless already changed)
            if ($grupo->getTipoDispositivo() === $this) {
                $grupo->setTipoDispositivo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rol[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Rol $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->setTipoDispositivo($this);
        }

        return $this;
    }

    public function removeRole(Rol $role): self
    {
        if ($this->roles->removeElement($role)) {
            // set the owning side to null (unless already changed)
            if ($role->getTipoDispositivo() === $this) {
                $role->setTipoDispositivo(null);
            }
        }

        return $this;
    }
}
