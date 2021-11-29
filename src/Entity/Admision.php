<?php

namespace App\Entity;

use App\Repository\AdmisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdmisionRepository::class)
 */
class Admision
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Dispositivo::class, inversedBy="admisiones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dispositivo;

    /**
     * @ORM\ManyToMany(targetEntity=PersonaFisica::class, inversedBy="admisiones")
     */
    private $personaFisica;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAlta;

    public function __construct()
    {
        $this->personaFisica = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDispositivo(): ?Dispositivo
    {
        return $this->dispositivo;
    }

    public function setDispositivo(?Dispositivo $dispositivo): self
    {
        $this->dispositivo = $dispositivo;

        return $this;
    }

    /**
     * @return Collection|PersonaFisica[]
     */
    public function getPersonaFisica(): Collection
    {
        return $this->personaFisica;
    }

    public function addPersonaFisica(PersonaFisica $personaFisica): self
    {
        if (!$this->personaFisica->contains($personaFisica)) {
            $this->personaFisica[] = $personaFisica;
        }

        return $this;
    }

    public function removePersonaFisica(PersonaFisica $personaFisica): self
    {
        $this->personaFisica->removeElement($personaFisica);

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
}
