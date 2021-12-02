<?php

namespace App\Entity;

use App\Repository\InvitacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvitacionRepository::class)
 * @ORM\Table(name="invitacion")
 * @ORM\HasLifecycleCallbacks()
 */
class Invitacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Dispositivo::class, inversedBy="invitaciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dispositivo;

    /**
     * @ORM\ManyToOne(targetEntity=PersonaFisica::class, inversedBy="invitaciones", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $personaFisica;

    /**
     * @ORM\ManyToOne(targetEntity=PersonaFisica::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $origen;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaInvitacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $fechaUso;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $aceptada;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEliminacion;

    /*
     * @ORM\PrePersist
     */
    public function prePersist(){
        $this->fechaInvitacion = new \DateTime();
        $this->hash = md5(uniqid(rand(), true));
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

    public function getPersonaFisica(): ?PersonaFisica
    {
        return $this->personaFisica;
    }

    public function setPersonaFisica(?PersonaFisica $personaFisica): self
    {
        $this->personaFisica = $personaFisica;

        return $this;
    }

    public function getOrigen(): ?PersonaFisica
    {
        return $this->origen;
    }

    public function setOrigen(?PersonaFisica $origen): self
    {
        $this->origen = $origen;

        return $this;
    }

    public function getFechaInvitacion(): ?\DateTimeInterface
    {
        return $this->fechaInvitacion;
    }

    public function setFechaInvitacion(
        \DateTimeInterface $fechaInvitacion
    ): self {
        $this->fechaInvitacion = $fechaInvitacion;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getFechaUso(): ?\DateTimeInterface
    {
        return $this->fechaUso;
    }

    public function setFechaUso(\DateTimeInterface $fechaUso): self
    {
        $this->fechaUso = $fechaUso;

        return $this;
    }

    public function getAceptada(): ?bool
    {
        return $this->aceptada;
    }

    public function setAceptada(?bool $aceptada): self
    {
        $this->aceptada = $aceptada;

        return $this;
    }

    public function getFechaEliminacion(): ?\DateTimeInterface
    {
        return $this->fechaEliminacion;
    }

    public function setFechaEliminacion(
        ?\DateTimeInterface $fechaEliminacion
    ): self {
        $this->fechaEliminacion = $fechaEliminacion;

        return $this;
    }
}
