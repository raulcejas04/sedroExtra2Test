<?php

namespace App\Entity;

use App\Repository\GrupoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GrupoRepository::class)
 */
class Grupo {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="grupos")
     */
    private $usuarios;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $KeycloakGroupId;

    /**
     * @ORM\OneToOne(targetEntity=TipoDispositivo::class, mappedBy="grupo", cascade={"persist", "remove"})
     */
    private $tipoDispositivo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEliminacion;

    public function __toString() {
        return $this->getNombre();
    }

    public function __construct() {
        $this->usuarios = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsuarios(): Collection {
        return $this->usuarios;
    }

    public function addUsuario(User $usuario): self {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios[] = $usuario;
            $usuario->addGrupo($this);
        }

        return $this;
    }

    public function removeUsuario(User $usuario): self {
        if ($this->usuarios->removeElement($usuario)) {
            $usuario->removeGrupo($this);
        }


        return $this;
    }

    public function getKeycloakGroupId(): ?string {
        return $this->KeycloakGroupId;
    }

    public function setKeycloakGroupId(?string $KeycloakGroupId): self {
        $this->KeycloakGroupId = $KeycloakGroupId;

        return $this;
    }

    public function getTipoDispositivo(): ?TipoDispositivo {
        return $this->tipoDispositivo;
    }

    public function setTipoDispositivo(?TipoDispositivo $tipoDispositivo): self {
        // unset the owning side of the relation if necessary
        if ($tipoDispositivo === null && $this->tipoDispositivo !== null) {
            $this->tipoDispositivo->setGrupo(null);
        }

        // set the owning side of the relation if necessary
        if ($tipoDispositivo !== null && $tipoDispositivo->getGrupo() !== $this) {
            $tipoDispositivo->setGrupo($this);
        }

        $this->tipoDispositivo = $tipoDispositivo;

        return $this;
    }

    public function getNombre(): ?string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self {
        $this->nombre = $nombre;

        return $this;
    }

    public function getFechaEliminacion(): ?\DateTimeInterface {
        return $this->fechaEliminacion;
    }

    public function setFechaEliminacion(?\DateTimeInterface $fechaEliminacion): self {
        $this->fechaEliminacion = $fechaEliminacion;

        return $this;
    }

}
