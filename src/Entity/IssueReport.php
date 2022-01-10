<?php

namespace App\Entity;

use App\Repository\IssueReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IssueReportRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class IssueReport
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
    private $titulo;

    /**
     * @ORM\Column(type="text")
     */
    private $issue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creacion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="issuesReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\PrePersist
     */
    public function setPrePersistValues(): void
    {
        $this->creacion = new \DateTimeImmutable();
    }

    public function __toString()
    {
        return $this->getTitulo();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIssue(): ?string
    {
        return $this->issue;
    }

    public function setIssue(string $issue): self
    {
        $this->issue = $issue;

        return $this;
    }

    public function getCreacion(): ?\DateTimeInterface
    {
        return $this->creacion;
    }

    public function setCreacion(?\DateTimeInterface $creacion): self
    {
        $this->creacion = $creacion;

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

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }
}
