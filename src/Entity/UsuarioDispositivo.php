<?php

namespace App\Entity;

use App\Repository\UsuarioDispositivoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsuarioDispositivoRepository::class)
 */
class UsuarioDispositivo
{

    CONST NIVEL_1 = 1;//PASTOR JOAO
    CONST NIVEL_2 = 2;//PASTOR GIMENEZ 
    CONST NIVEL_3 = 3;//JONY

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="usuarioDispositivos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Dispositivo::class, inversedBy="usuarioDispositivos")
     */
    private $dispositivo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $auditoria;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaAlta;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaBaja;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEliminacion;

    /**
     * @ORM\Column(type="integer")
     */
    private $nivel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getDispositivo(): ?Dispositivo
    {
        return $this->dispositivo;
    }

    public function setDispositivo(?Dispositivo $dispositivo): self
    {
        $this->dispositivo = $dispositivo;

        return $this;
    }

    public function getAuditoria(): ?string
    {
        return $this->auditoria;
    }

    public function setAuditoria(?string $auditoria): self
    {
        $this->auditoria = $auditoria;

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

    public function getFechaEliminacion(): ?\DateTimeInterface
    {
        return $this->fechaEliminacion;
    }

    public function setFechaEliminacion(?\DateTimeInterface $fechaEliminacion): self
    {
        $this->fechaEliminacion = $fechaEliminacion;

        return $this;
    }

    public function getNivel(): ?int
    {
        return $this->nivel;
    }

    public function setNivel(int $nivel): self
    {
        $this->nivel = $nivel;

        return $this;
    }
}
