<?php

namespace App\Entity;

use App\Repository\TipoDocumentoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoDocumentoRepository::class)
 */
class TipoDocumento
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
    private $tipoDocumento;

    /**
     * @ORM\OneToMany(targetEntity=PersonaFisica::class, mappedBy="tipoDocumento")
     */
    private $personaFisicas;

    public function __construct()
    {
        $this->personaFisicas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTipoDocumento();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoDocumento(): ?string
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(string $tipoDocumento): self
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * @return Collection|PersonaFisica[]
     */
    public function getPersonaFisicas(): Collection
    {
        return $this->personaFisicas;
    }

    public function addPersonaFisica(PersonaFisica $personaFisica): self
    {
        if (!$this->personaFisicas->contains($personaFisica)) {
            $this->personaFisicas[] = $personaFisica;
            $personaFisica->setTipoDocumento($this);
        }

        return $this;
    }

    public function removePersonaFisica(PersonaFisica $personaFisica): self
    {
        if ($this->personaFisicas->removeElement($personaFisica)) {
            // set the owning side to null (unless already changed)
            if ($personaFisica->getTipoDocumento() === $this) {
                $personaFisica->setTipoDocumento(null);
            }
        }

        return $this;
    }
}
