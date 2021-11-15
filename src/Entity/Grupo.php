<?php

namespace App\Entity;

use App\Repository\GrupoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GrupoRepository::class)
 */
class Grupo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="usuarios")
     */
    private $usuarios;

    /**
     * @ORM\ManyToOne(targetEntity=TipoDispositivo::class, inversedBy="grupos")
     */
    private $tipoDispositivo;

    /**
     * @ORM\ManyToMany(targetEntity=Rol::class, inversedBy="grupos")
     */
    private $roles;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(User $usuario): self
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios[] = $usuario;
        }

        return $this;
    }

    public function removeUsuario(User $usuario): self
    {
        $this->usuarios->removeElement($usuario);

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
        }

        return $this;
    }

    public function removeRole(Rol $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }
}
