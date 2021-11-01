<?php

namespace App\Entity;

use App\Repository\SexoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SexoRepository::class)
 */
class Sexo
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
    private $sexo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity=PersonaFisica::class, mappedBy="sexo")
     */
    private $personasFisicas;

    public function __construct()
    {
        $this->personasFisicas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getSexo();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

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
            $personasFisica->setSexo($this);
        }

        return $this;
    }

    public function removePersonasFisica(PersonaFisica $personasFisica): self
    {
        if ($this->personasFisicas->removeElement($personasFisica)) {
            // set the owning side to null (unless already changed)
            if ($personasFisica->getSexo() === $this) {
                $personasFisica->setSexo(null);
            }
        }

        return $this;
    }
}
