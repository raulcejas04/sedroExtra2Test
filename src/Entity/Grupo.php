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
     * @ORM\OneToMany(targetEntity=UserGrupo::class, mappedBy="grupo",cascade={"persist"})
     */
    private $groupUsers;

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
        $this->groupUsers = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
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

    /**
     * @return Collection|UserGrupo[]
     */
    public function getGroupUsers(): Collection
    {
        return $this->groupUsers;
    }

    public function addGroupUser(UserGrupo $groupUser): self
    {
        if (!$this->groupUsers->contains($groupUser)) {
            $this->groupUsers[] = $groupUser;
            $groupUser->setGrupo($this);
        }

        return $this;
    }

    public function removeGroupUser(UserGrupo $groupUser): self
    {
        if ($this->groupUsers->removeElement($groupUser)) {
            // set the owning side to null (unless already changed)
            if ($groupUser->getGrupo() === $this) {
                $groupUser->setGrupo(null);
            }
        }

        return $this;
    }

}
