<?php

namespace App\Entity;

use App\Repository\PersonaJuridicaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonaJuridicaRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class PersonaJuridica
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=13)
     */
    private $cuit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $razonSocial;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaAlta;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaBaja;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="personaJuridica")
     */
    private $solicitudes;

    /**
     * @ORM\OneToMany(targetEntity=Dispositivo::class, mappedBy="personaJuridica")
     */
    private $dispositivos;

    /**
     * @ORM\OneToMany(targetEntity=Representacion::class, mappedBy="personaJuridica")
     */
    private $representaciones;

    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
        $this->dispositivos = new ArrayCollection();
        $this->representaciones = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getRazonSocial();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCuit(): ?string
    {
        return $this->cuit;
    }

    public function setCuit(string $cuit): self
    {
        $this->cuit = $cuit;

        return $this;
    }

    public function getRazonSocial(): ?string
    {
        return $this->razonSocial;
    }

    public function setRazonSocial(string $razonSocial): self
    {
        $this->razonSocial = $razonSocial;

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
            $solicitude->setPersonaJuridica($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getPersonaJuridica() === $this) {
                $solicitude->setPersonaJuridica(null);
            }
        }

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
            $dispositivo->setPersonaJuridica($this);
        }

        return $this;
    }

    public function removeDispositivo(Dispositivo $dispositivo): self
    {
        if ($this->dispositivos->removeElement($dispositivo)) {
            // set the owning side to null (unless already changed)
            if ($dispositivo->getPersonaJuridica() === $this) {
                $dispositivo->setPersonaJuridica(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Representacion[]
     */
    public function getRepresentaciones(): Collection
    {
        return $this->representaciones;
    }

    public function addRepresentacione(Representacion $representacione): self
    {
        if (!$this->representaciones->contains($representacione)) {
            $this->representaciones[] = $representacione;
            $representacione->setPersonaJuridica($this);
        }

        return $this;
    }

    public function removeRepresentacione(Representacion $representacione): self
    {
        if ($this->representaciones->removeElement($representacione)) {
            // set the owning side to null (unless already changed)
            if ($representacione->getPersonaJuridica() === $this) {
                $representacione->setPersonaJuridica(null);
            }
        }

        return $this;
    }
}
