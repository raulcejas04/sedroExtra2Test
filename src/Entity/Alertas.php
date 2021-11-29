<?php

namespace App\Entity;

use App\Repository\AlertasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Alertas
 *
 * @ORM\Table(name="alertas")
 * @ORM\Entity(repositoryClass=AlertasRepository::class)
 */
class Alertas
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="alertas_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="alert_type", type="string", length=3, nullable=true)
     */
    private $alertType;

    /**
     * @var int|null
     *
     * @ORM\Column(name="object_type_id", type="integer", nullable=true)
     */
    private $objectTypeId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="persona_juridica_id", type="integer", nullable=true)
     */
    private $personaJuridicaId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="persona_fisica_id", type="integer", nullable=true)
     */
    private $personaFisicaId;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fechavto", type="date", nullable=true)
     */
    private $fechavto;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=false)
     */
    private $titulo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="string", length=1024, nullable=true)
     */
    private $descripcion;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=true)
     */
    private $createdat;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updatedat", type="datetime", nullable=true)
     */
    private $updatedat;

    /**
     * @var int|null
     *
     * @ORM\Column(name="createdby", type="integer", nullable=true)
     */
    private $createdby;

    /**
     * @var int|null
     *
     * @ORM\Column(name="updatedby", type="integer", nullable=true)
     */
    private $updatedby;

    /**
     * @var int|null
     *
     * @ORM\Column(name="readed", type="smallint", nullable=true)
     */
    private $readed;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="alertas")
     */
    private $usuario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlertType(): ?string
    {
        return $this->alertType;
    }

    public function setAlertType(?string $alertType): self
    {
        $this->alertType = $alertType;

        return $this;
    }

    public function getObjectTypeId(): ?int
    {
        return $this->objectTypeId;
    }

    public function setObjectTypeId(?int $objectTypeId): self
    {
        $this->objectTypeId = $objectTypeId;

        return $this;
    }

    public function getPersonaJuridicaId(): ?int
    {
        return $this->personaJuridicaId;
    }

    public function setPersonaJuridicaId(?int $personaJuridicaId): self
    {
        $this->personaJuridicaId = $personaJuridicaId;

        return $this;
    }

    public function getPersonaFisicaId(): ?int
    {
        return $this->personaFisicaId;
    }

    public function setPersonaFisicaId(?int $personaFisicaId): self
    {
        $this->personaFisicaId = $personaFisicaId;

        return $this;
    }

    public function getFechavto(): ?\DateTimeInterface
    {
        return $this->fechavto;
    }

    public function setFechavto(?\DateTimeInterface $fechavto): self
    {
        $this->fechavto = $fechavto;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(?\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(?\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getCreatedby(): ?int
    {
        return $this->createdby;
    }

    public function setCreatedby(?int $createdby): self
    {
        $this->createdby = $createdby;

        return $this;
    }

    public function getUpdatedby(): ?int
    {
        return $this->updatedby;
    }

    public function setUpdatedby(?int $updatedby): self
    {
        $this->updatedby = $updatedby;

        return $this;
    }

    public function getReaded(): ?int
    {
        return $this->readed;
    }

    public function setReaded(?int $readed): self
    {
        $this->readed = $readed;

        return $this;
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


}
