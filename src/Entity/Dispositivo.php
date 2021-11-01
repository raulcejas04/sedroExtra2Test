<?php

namespace App\Entity;

use App\Repository\DispositivoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DispositivoRepository::class)
 */
class Dispositivo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nicname;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaAlta;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaBaja;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="dispositivo")
     */
    private $solicitudes;

    /**
     * @ORM\ManyToOne(targetEntity=PersonaJuridica::class, inversedBy="dispositivos", cascade={"persist"})
     */
    private $personaJuridica;

    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNicname();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNicname(): ?string
    {
        return $this->nicname;
    }

    public function setNicname(string $nicname): self
    {
        $this->nicname = $nicname;

        return $this;
    }

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(?\DateTimeInterface $fechaAlta): self
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    public function getFechaBaja(): ?\DateTimeInterface
    {
        return $this->fechaBaja;
    }

    public function setFechaBaja(?\DateTimeInterface $fechaBaja): self
    {
        $this->fechaBaja = $fechaBaja;

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
            $solicitude->setDispositivo($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getDispositivo() === $this) {
                $solicitude->setDispositivo(null);
            }
        }

        return $this;
    }

    public function getPersonaJuridica(): ?PersonaJuridica
    {
        return $this->personaJuridica;
    }

    public function setPersonaJuridica(?PersonaJuridica $personaJuridica): self
    {
        $this->personaJuridica = $personaJuridica;

        return $this;
    }
}
