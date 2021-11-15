<?php

namespace App\Entity;

use App\Repository\EstadoCivilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstadoCivilRepository::class)
 */
class EstadoCivil
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
    private $estadoCivil;

    /**
     * @ORM\OneToMany(targetEntity=PersonaFisica::class, mappedBy="estadoCivil")
     */
    private $personasFisicas;

    public function __construct()
    {
        $this->personasFisicas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getEstadoCivil();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstadoCivil(): ?string
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(string $estadoCivil): self
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    /**
     * @return Collection|PersonaFisica[]
     */
    public function getPersonasFisicas(): Collection
    {
        return $this->personasFisicas;
    }

    public function addPersonasFisica(PersonaFisica $personasFisica): self
    {
        if (!$this->personasFisicas->contains($personasFisica)) {
            $this->personasFisicas[] = $personasFisica;
            $personasFisica->setEstadoCivil($this);
        }

        return $this;
    }

    public function removePersonasFisica(PersonaFisica $personasFisica): self
    {
        if ($this->personasFisicas->removeElement($personasFisica)) {
            // set the owning side to null (unless already changed)
            if ($personasFisica->getEstadoCivil() === $this) {
                $personasFisica->setEstadoCivil(null);
            }
        }

        return $this;
    }
}
