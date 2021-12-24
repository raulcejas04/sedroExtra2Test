<?php

namespace App\Entity;

use App\Repository\PersonaFisicaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PersonaFisicaRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"cuitCuil"},repositoryMethod="getCuitCuil", message="El CUIT/CUIL ingresado ya existe.")
 */
class PersonaFisica
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
    private $apellido;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nroDoc;

    /**
     * @ORM\Column(type="string", length=14)
     */
    private $cuitCuil;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaNac;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="personaFisica",cascade={"persist"})
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=TipoCuitCuil::class, inversedBy="personasFisicas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoCuitCuil;

    /**
     * @ORM\ManyToOne(targetEntity=Sexo::class, inversedBy="personasFisicas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sexo;

    /**
     * @ORM\ManyToOne(targetEntity=EstadoCivil::class, inversedBy="personasFisicas")
     */
    private $estadoCivil;

    /**
     * @ORM\ManyToOne(targetEntity=Nacionalidad::class, inversedBy="personasFisicas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nacionalidad;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="personaFisica")
     */
    private $solicitudes;

    /**
     * @ORM\ManyToOne(targetEntity=TipoDocumento::class, inversedBy="personaFisicas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoDocumento;

    /**
     * @ORM\OneToMany(targetEntity=Representacion::class, mappedBy="personaFisica")
     */
    private $representaciones;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEliminacion;

    /**
     * @ORM\ManyToMany(targetEntity=Admision::class, mappedBy="personaFisica")
     */
    private $admisiones;

    /**
     * @ORM\OneToMany(targetEntity=Invitacion::class, mappedBy="personaFisica")
     */
    private $invitaciones;

    /**
     * @ORM\OneToMany(targetEntity=DispositivoResponsable::class, mappedBy="personaFisica")
     */
    private $dispositivosResponsable;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->solicitudes = new ArrayCollection();
        $this->representaciones = new ArrayCollection();
        $this->admisiones = new ArrayCollection();
        $this->invitaciones = new ArrayCollection();
        $this->dispositivosResponsable = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getApellido() . ', ' . $this->getNombres();
    }

    /**
     * @ORM\PrePersist
     */
    public function setUppers(): void
    {
        $this->setApellido(mb_convert_case($this->getApellido(), MB_CASE_UPPER, "UTF-8"));
        $this->setNombres(mb_convert_case($this->getNombres(), MB_CASE_TITLE, "UTF-8"));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getNroDoc(): ?string
    {
        return $this->nroDoc;
    }

    public function setNroDoc(string $nroDoc): self
    {
        $this->nroDoc = $nroDoc;

        return $this;
    }

    public function getCuitCuil(): ?string
    {
        return $this->cuitCuil;
    }

    public function setCuitCuil(string $cuitCuil): self
    {
        $this->cuitCuil = $cuitCuil;

        return $this;
    }

    public function getFechaNac(): ?\DateTimeInterface
    {
        return $this->fechaNac;
    }

    public function setFechaNac(?\DateTimeInterface $fechaNac): self
    {
        $this->fechaNac = $fechaNac;

        return $this;
    }

    public function getTipoCuitCuil(): ?TipoCuitCuil
    {
        return $this->tipoCuitCuil;
    }

    public function setTipoCuitCuil(?TipoCuitCuil $tipoCuitCuil): self
    {
        $this->tipoCuitCuil = $tipoCuitCuil;

        return $this;
    }

    public function getSexo(): ?Sexo
    {
        return $this->sexo;
    }

    public function setSexo(?Sexo $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getEstadoCivil(): ?EstadoCivil
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(?EstadoCivil $estadoCivil): self
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    public function getNacionalidad(): ?Nacionalidad
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad(?Nacionalidad $nacionalidad): self
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    /**
     * @return Collection|Solicitud[]
     */
    public function getSolicitudes(): Collection
    {
        return $this->solicitudes;
    }

    public function addSolicitude(Solicitud $solicitude): self
    {
        if (!$this->solicitudes->contains($solicitude)) {
            $this->solicitudes[] = $solicitude;
            $solicitude->setPersonaFisica($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getPersonaFisica() === $this) {
                $solicitude->setPersonaFisica(null);
            }
        }

        return $this;
    }

    public function getTipoDocumento(): ?TipoDocumento
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(?TipoDocumento $tipoDocumento): self
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * @return Collection|Representacion[]
     */
    public function getRepresentaciones(): Collection
    {
        return $this->representaciones;
    }

    public function addRepresentacione(Representacion $representacione): self
    {
        if (!$this->representaciones->contains($representacione)) {
            $this->representaciones[] = $representacione;
            $representacione->setPersonaFisica($this);
        }

        return $this;
    }

    public function removeRepresentacione(Representacion $representacione): self
    {
        if ($this->representaciones->removeElement($representacione)) {
            // set the owning side to null (unless already changed)
            if ($representacione->getPersonaFisica() === $this) {
                $representacione->setPersonaFisica(null);
            }
        }

        return $this;
    }

    public function getFechaEliminacion(): ?\DateTimeInterface
    {
        return $this->fechaEliminacion;
    }

    public function setFechaEliminacion(?\DateTimeInterface $fechaEliminacion): self
    {
        $this->fechaEliminacion = $fechaEliminacion;

        return $this;
    }

    /**
     * @return Collection|Admision[]
     */
    public function getAdmisiones(): Collection
    {
        return $this->admisiones;
    }

    public function addAdmisione(Admision $admisione): self
    {
        if (!$this->admisiones->contains($admisione)) {
            $this->admisiones[] = $admisione;
            $admisione->addPersonaFisica($this);
        }

        return $this;
    }

    public function removeAdmisione(Admision $admisione): self
    {
        if ($this->admisiones->removeElement($admisione)) {
            $admisione->removePersonaFisica($this);
        }

        return $this;
    }

    /**
     * @return Collection|Invitacion[]
     */
    public function getInvitaciones(): Collection
    {
        return $this->invitaciones;
    }

    public function addInvitacione(Invitacion $invitacione): self
    {
        if (!$this->invitaciones->contains($invitacione)) {
            $this->invitaciones[] = $invitacione;
            $invitacione->setPersonaFisica($this);
        }

        return $this;
    }

    public function removeInvitacione(Invitacion $invitacione): self
    {
        if ($this->invitaciones->removeElement($invitacione)) {
            // set the owning side to null (unless already changed)
            if ($invitacione->getPersonaFisica() === $this) {
                $invitacione->setPersonaFisica(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setPersonaFisica($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getPersonaFisica() === $this) {
                $user->setPersonaFisica(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DispositivoResponsable[]
     */
    public function getDispositivosResponsable(): Collection
    {
        return $this->dispositivosResponsable;
    }

    public function addDispositivosResponsable(DispositivoResponsable $dispositivosResponsable): self
    {
        if (!$this->dispositivosResponsable->contains($dispositivosResponsable)) {
            $this->dispositivosResponsable[] = $dispositivosResponsable;
            $dispositivosResponsable->setPersonaFisica($this);
        }

        return $this;
    }

    public function removeDispositivosResponsable(DispositivoResponsable $dispositivosResponsable): self
    {
        if ($this->dispositivosResponsable->removeElement($dispositivosResponsable)) {
            // set the owning side to null (unless already changed)
            if ($dispositivosResponsable->getPersonaFisica() === $this) {
                $dispositivosResponsable->setPersonaFisica(null);
            }
        }

        return $this;
    }

}
