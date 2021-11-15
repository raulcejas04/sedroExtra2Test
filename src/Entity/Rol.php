<?php

namespace App\Entity;

use App\Repository\RolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RolRepository::class)
 */
class Rol
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
    private $nombreRol;

    /**
     * @ORM\ManyToOne(targetEntity=TipoDispositivo::class, inversedBy="roles")
     */
    private $tipoDispositivo;

    /**
     * @ORM\ManyToMany(targetEntity=Grupo::class, mappedBy="roles")
     */
    private $grupos;

    public function __construct()
    {
        $this->grupos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreRol(): ?string
    {
        return $this->nombreRol;
    }

    public function setNombreRol(string $nombreRol): self
    {
        $this->nombreRol = $nombreRol;

        return $this;
    }

    public function getTipoDispositivo(): ?TipoDispositivo
    {
        return $this->tipoDispositivo;
    }

    public function setTipoDispositivo(?TipoDispositivo $tipoDispositivo): self
    {
        $this->tipoDispositivo = $tipoDispositivo;

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
            $grupo->addRole($this);
        }

        return $this;
    }

    public function removeGrupo(Grupo $grupo): self
    {
        if ($this->grupos->removeElement($grupo)) {
            $grupo->removeRole($this);
        }

        return $this;
    }
}
