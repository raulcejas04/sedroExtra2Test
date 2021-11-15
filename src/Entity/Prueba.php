<?php

namespace App\Entity;

use App\Repository\PruebaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PruebaRepository::class)
 */
class Prueba
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $campoString;

    /**
     * @ORM\Column(type="integer")
     */
    private $campoInteger;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $campoEmail;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $campoTexto;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $campoMail;

    /**
     * @ORM\Column(type="float")
     */
    private $campoReal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampoString(): ?string
    {
        return $this->campoString;
    }

    public function setCampoString(string $campoString): self
    {
        $this->campoString = $campoString;

        return $this;
    }

    public function getCampoInteger(): ?int
    {
        return $this->campoInteger;
    }

    public function setCampoInteger(int $campoInteger): self
    {
        $this->campoInteger = $campoInteger;

        return $this;
    }

    public function getCampoEmail(): ?string
    {
        return $this->campoEmail;
    }

    public function setCampoEmail(?string $campoEmail): self
    {
        $this->campoEmail = $campoEmail;

        return $this;
    }

    public function getCampoTexto(): ?string
    {
        return $this->campoTexto;
    }

    public function setCampoTexto(?string $campoTexto): self
    {
        $this->campoTexto = $campoTexto;

        return $this;
    }

    public function getCampoMail(): ?string
    {
        return $this->campoMail;
    }

    public function setCampoMail(string $campoMail): self
    {
        $this->campoMail = $campoMail;

        return $this;
    }

    public function getCampoReal(): ?float
    {
        return $this->campoReal;
    }

    public function setCampoReal(float $campoReal): self
    {
        $this->campoReal = $campoReal;

        return $this;
    }
}
