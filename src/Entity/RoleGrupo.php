<?php

namespace App\Entity;

use App\Repository\RoleGrupoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoleGrupoRepository::class)
 */
class RoleGrupo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="rolesGrupo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity=Grupo::class, inversedBy="rolesGrupo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $grupo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getGrupo(): ?Grupo
    {
        return $this->grupo;
    }

    public function setGrupo(?Grupo $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }
}
