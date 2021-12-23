<?php

namespace App\AuxEntities;

use App\Entity\PersonaFisica;
use App\Entity\PersonaJuridica;

class PasoDos
{
    private $dispositivo;
    private $personaFisica;
    private $personaJuridica;

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

    /**
     * Get the value of dispositivo
     */ 
    public function getDispositivo()
    {
        return $this->dispositivo;
    }

    /**
     * Set the value of dispositivo
     *
     * @return  self
     */ 
    public function setDispositivo($dispositivo)
    {
        $this->dispositivo = $dispositivo;

        return $this;
    }
}
