<?php

namespace App\Entity;

use App\Repository\UserRealmRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRealmRepository::class)
 */
class UserRealm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Realm::class, inversedBy="userRealms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $realm;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userRealms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEliminacion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealm(): ?Realm
    {
        return $this->realm;
    }

    public function setRealm(?Realm $realm): self
    {
        $this->realm = $realm;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

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
}
