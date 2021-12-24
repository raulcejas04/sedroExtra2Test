<?php

namespace App\Entity;

use App\Repository\DispositivoResponsableRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DispositivoResponsableRepository::class)
 */
class DispositivoResponsable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PersonaFisica::class, inversedBy="dispositivosResponsable")
     */
    private $personaFisica;

    /**
     * @ORM\ManyToOne(targetEntity=Dispositivo::class, inversedBy="responsables")
     */
    private $dispositivo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDispositivo()
    {
        return $this->dispositivo;
    }

    public function setDispositivo($dispositivo)
    {
        $this->dispositivo = $dispositivo;

        return $this;
    }

    public function getOwner(): ?bool
    {
        return $this->owner;
    }

    public function setOwner(bool $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get the value of usuario
     */ 
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set the value of usuario
     *
     * @return  self
     */ 
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

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
}
