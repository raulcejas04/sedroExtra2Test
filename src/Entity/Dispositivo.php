<?php

namespace App\Entity;

use App\Repository\DispositivoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DispositivoRepository::class)
 */
class Dispositivo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nicname;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaAlta;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaBaja;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="dispositivo")
     */
    private $solicitudes;

    /**
     * @ORM\ManyToOne(targetEntity=PersonaJuridica::class, inversedBy="dispositivos", cascade={"persist"})
     */
    private $personaJuridica;

    /**
     * @ORM\OneToMany(targetEntity=UsuarioDispositivo::class, mappedBy="dispositivo")
     */
    private $usuarioDispositivos;

    /**
     * @ORM\ManyToOne(targetEntity=TipoDispositivo::class, inversedBy="dispositivos")
     */
    private $tipoDispositivo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEliminacion;

    /**
     * @ORM\OneToMany(targetEntity=Admision::class, mappedBy="dispositivo")
     */
    private $admisiones;

    /**
     * @ORM\OneToMany(targetEntity=Invitacion::class, mappedBy="dispositivo")
     */
    private $invitaciones;

    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
        $this->usuarioDispositivos = new ArrayCollection();
        $this->admisiones = new ArrayCollection();
        $this->invitaciones = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNicname();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNicname(): ?string
    {
        return $this->nicname;
    }

    public function setNicname(string $nicname): self
    {
        $this->nicname = $nicname;

        return $this;
    }

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(?\DateTimeInterface $fechaAlta): self
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    public function getFechaBaja(): ?\DateTimeInterface
    {
        return $this->fechaBaja;
    }

    public function setFechaBaja(?\DateTimeInterface $fechaBaja): self
    {
        $this->fechaBaja = $fechaBaja;

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
            $solicitude->setDispositivo($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getDispositivo() === $this) {
                $solicitude->setDispositivo(null);
            }
        }

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
     * @return Collection|UsuarioDispositivo[]
     */
    public function getUsuarioDispositivos(): Collection
    {
        return $this->usuarioDispositivos;
    }

    public function addUsuarioDispositivo(UsuarioDispositivo $usuarioDispositivo): self
    {
        if (!$this->usuarioDispositivos->contains($usuarioDispositivo)) {
            $this->usuarioDispositivos[] = $usuarioDispositivo;
            $usuarioDispositivo->setDispositivo($this);
        }

        return $this;
    }

    public function removeUsuarioDispositivo(UsuarioDispositivo $usuarioDispositivo): self
    {
        if ($this->usuarioDispositivos->removeElement($usuarioDispositivo)) {
            // set the owning side to null (unless already changed)
            if ($usuarioDispositivo->getDispositivo() === $this) {
                $usuarioDispositivo->setDispositivo(null);
            }
        }

        return $this;
    }

    public function getTipoDispositivo(): ?TipoDispositivo
    {
        return $this->tipoDispositivo;
    }

    public function setTipoDispositivo(?TipoDispositivo $tipoDispositivo): self
    {
        $this->tipoDispositivo = $tipoDispositivo;

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
            $admisione->setDispositivo($this);
        }

        return $this;
    }

    public function removeAdmisione(Admision $admisione): self
    {
        if ($this->admisiones->removeElement($admisione)) {
            // set the owning side to null (unless already changed)
            if ($admisione->getDispositivo() === $this) {
                $admisione->setDispositivo(null);
            }
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
            $invitacione->setDispositivo($this);
        }

        return $this;
    }

    public function removeInvitacione(Invitacion $invitacione): self
    {
        if ($this->invitaciones->removeElement($invitacione)) {
            // set the owning side to null (unless already changed)
            if ($invitacione->getDispositivo() === $this) {
                $invitacione->setDispositivo(null);
            }
        }

        return $this;
    }
}
