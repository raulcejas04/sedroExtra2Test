<?php

namespace App\Entity;

use App\Repository\TipoCuitCuilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoCuitCuilRepository::class)
 */
class TipoCuitCuil
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
    private $tipoCuitCuil;

    /**
     * @ORM\OneToMany(targetEntity=PersonaFisica::class, mappedBy="tipoCuitCuil")
     */
    private $personasFisicas;

    public function __construct()
    {
        $this->personasFisicas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTipoCuitCuil();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoCuitCuil(): ?string
    {
        return $this->tipoCuitCuil;
    }

    public function setTipoCuitCuil(string $tipoCuitCuil): self
    {
        $this->tipoCuitCuil = $tipoCuitCuil;

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
            $personasFisica->setTipoCuitCuil($this);
        }

        return $this;
    }

    public function removePersonasFisica(PersonaFisica $personasFisica): self
    {
        if ($this->personasFisicas->removeElement($personasFisica)) {
            // set the owning side to null (unless already changed)
            if ($personasFisica->getTipoCuitCuil() === $this) {
                $personasFisica->setTipoCuitCuil(null);
            }
        }

        return $this;
    }
}
