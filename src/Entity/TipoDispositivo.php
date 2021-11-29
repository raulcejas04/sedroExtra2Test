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
     * @ORM\OneToOne(targetEntity=Grupo::class, inversedBy="tipoDispositivo", cascade={"persist", "remove"})
     */
    private $grupo;

    public function __construct()
    {
        $this->dispositivos = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNombre();
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
