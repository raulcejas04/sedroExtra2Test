<?php

namespace App\Entity;

use App\Repository\NacionalidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NacionalidadRepository::class)
 */
class Nacionalidad
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
    private $pais;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $codigo;

    /**
     * @ORM\OneToMany(targetEntity=PersonaFisica::class, mappedBy="nacionalidad")
     */
    private $personasFisicas;

    public function __construct()
    {
        $this->personasFisicas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getPais();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(string $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): self
    {
        $this->codigo = $codigo;

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
            $personasFisica->setNacionalidad($this);
        }

        return $this;
    }

    public function removePersonasFisica(PersonaFisica $personasFisica): self
    {
        if ($this->personasFisicas->removeElement($personasFisica)) {
            // set the owning side to null (unless already changed)
            if ($personasFisica->getNacionalidad() === $this) {
                $personasFisica->setNacionalidad(null);
            }
        }

        return $this;
    }
}
