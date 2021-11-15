<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`usuario`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $KeycloakId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\ManyToOne(targetEntity=Realm::class, inversedBy="users")
     */
    private $realm;

    /**
     * @ORM\ManyToOne(targetEntity=PersonaFisica::class, inversedBy="users")
     */
    private $personaFisica;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="usuario")
     */
    private $solicitudes;

    /**
     * @ORM\OneToMany(targetEntity=UsuarioDispositivo::class, mappedBy="usuario")
     */
    private $usuarioDispositivos;

    /**
     * @ORM\ManyToMany(targetEntity=Grupo::class, mappedBy="usuarios")
     */
    private $usuarios;

    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
        $this->usuarioDispositivos = new ArrayCollection();
        $this->usuarios = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getUserIdentifier();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getKeycloakId(): ?string
    {
        return $this->KeycloakId;
    }

    public function setKeycloakId(?string $KeycloakId): self
    {
        $this->KeycloakId = $KeycloakId;

        return $this;
    }

    //public function setUsername(string $username): self
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    public function getRealm(): ?Realm
    {
        return $this->realm;
    }

    public function setRealm(?Realm $realm): self
    {
        $this->realm = $realm;

        return $this;
    }

    public function getPersonaFisica(): ?PersonaFisica
    {
        return $this->personaFisica;
    }

    public function setPersonaFisica(?PersonaFisica $personaFisica): self
    {
        $this->personaFisica = $personaFisica;

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
            $solicitude->setUsuario($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getUsuario() === $this) {
                $solicitude->setUsuario(null);
            }
        }

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
            $usuarioDispositivo->setUsuario($this);
        }

        return $this;
    }

    public function removeUsuarioDispositivo(UsuarioDispositivo $usuarioDispositivo): self
    {
        if ($this->usuarioDispositivos->removeElement($usuarioDispositivo)) {
            // set the owning side to null (unless already changed)
            if ($usuarioDispositivo->getUsuario() === $this) {
                $usuarioDispositivo->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Grupo[]
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(Grupo $usuario): self
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios[] = $usuario;
            $usuario->addUsuario($this);
        }

        return $this;
    }

    public function removeUsuario(Grupo $usuario): self
    {
        if ($this->usuarios->removeElement($usuario)) {
            $usuario->removeUsuario($this);
        }

        return $this;
    }
}
