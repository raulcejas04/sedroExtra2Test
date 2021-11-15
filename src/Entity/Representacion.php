<?php

namespace App\Entity;

use App\Repository\RepresentacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RepresentacionRepository::class)
 */
class Representacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PersonaFisica::class, inversedBy="representaciones", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)     
     */
    private $personaFisica;

    /**
     * @ORM\ManyToOne(targetEntity=PersonaJuridica::class, inversedBy="representaciones", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)     
     */
    private $personaJuridica;

    public function getId(): ?int
    {
        return $this->id;
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
