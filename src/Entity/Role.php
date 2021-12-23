<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 */
class Role {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="company_id", type="integer", nullable=true, options={"comment"="Compania. Si esta en nulo, el rol puede ser seleccionado por cualquier compania."})
     */
    private $companyId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", length=30, nullable=true, options={"comment"="Codigo de Rol."})
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false, options={"comment"="Nombre del rol en idioma1."})
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="active", type="smallint", nullable=true, options={"default"="1","comment"="Activo si=1 no=0."})
     */
    private $active = '1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="createdby", type="integer", nullable=true, options={"comment"="usuario que creo el registro."})
     */
    private $createdby;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=true, options={"comment"="Fecha de creacion del registro."})
     */
    private $createdat;

    /**
     * @var int|null
     *
     * @ORM\Column(name="updatedby", type="integer", nullable=true, options={"comment"="Usuario que modifico el regisro."})
     */
    private $updatedby;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedat", type="datetime", nullable=true, options={"comment"="Fecha de modificacion del registro."})
     */
    private $updatedat;

    /**
     * @var int|null
     *
     * @ORM\Column(name="deletedby", type="integer", nullable=true, options={"comment"="Por quien fue borrado."})
     */
    private $deletedby;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deletedat", type="datetime", nullable=true, options={"comment"="Fecha y hora de borrado."})
     */
    private $deletedat;

    /**
     * @var int|null
     *
     * @ORM\Column(name="orderlist", type="integer", nullable=true, options={"default"="1","comment"="Orden"})
     */
    private $orderlist = 1;

    /**
     * @ORM\OneToMany(targetEntity=Rolemenu::class, mappedBy="role")
     */
    private $menus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keycloakRoleId;

    /**
     * @ORM\OneToMany(targetEntity=UserRole::class, mappedBy="role",cascade={"persist"})
     */
    private $userRoles;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEliminacion;

    /**
     * @ORM\OneToMany(targetEntity=RoleGrupo::class, mappedBy="role")
     */
    private $rolesGrupo;

    /**
     * @ORM\ManyToOne(targetEntity=Realm::class, inversedBy="roles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $realm;

    public function __toString() {
        return $this->getName();
    }

    public function __construct() {
        $this->menus = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->rolesGrupo = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getCompanyId(): ?int {
        return $this->companyId;
    }

    public function setCompanyId(?int $companyId): self {
        $this->companyId = $companyId;

        return $this;
    }

    public function getCode(): ?string {
        return $this->code;
    }

    public function setCode(?string $code): self {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getActive(): ?int {
        return $this->active;
    }

    public function setActive(?int $active): self {
        $this->active = $active;

        return $this;
    }

    public function getCreatedby(): ?int {
        return $this->createdby;
    }

    public function setCreatedby(?int $createdby): self {
        $this->createdby = $createdby;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self {
        $this->createdat = $createdat;

        return $this;
    }

    public function getUpdatedby(): ?int {
        return $this->updatedby;
    }

    public function setUpdatedby(?int $updatedby): self {
        $this->updatedby = $updatedby;

        return $this;
    }

    public function getUpdatedat(): ?\DateTimeInterface {
        return $this->updatedat;
    }

    public function setUpdatedat(\DateTimeInterface $updatedat): self {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getDeletedby(): ?int {
        return $this->deletedby;
    }

    public function setDeletedby(?int $deletedby): self {
        $this->deletedby = $deletedby;

        return $this;
    }

    public function getDeletedat(): ?\DateTimeInterface {
        return $this->deletedat;
    }

    public function setDeletedat(?\DateTimeInterface $deletedat): self {
        $this->deletedat = $deletedat;

        return $this;
    }

    public function getOrderlist(): ?int {
        return $this->orderlist;
    }

    public function setOrderlist(?int $orderlist): self {
        $this->orderlist = $orderlist;

        return $this;
    }

    /**
     * @return Collection|Rolemenu[]
     */
    public function getMenus(): Collection {
        return $this->menus;
    }

    public function addMenu(Rolemenu $menu): self {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setRole($this);
        }

        return $this;
    }

    public function removeMenu(Rolemenu $menu): self {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getRole() === $this) {
                $menu->setRole(null);
            }
        }

        return $this;
    }

    public function getMenu(): ?Rolemenu {
        return $this->menu;
    }

    public function setMenu(?Rolemenu $menu): self {
        // unset the owning side of the relation if necessary
        if ($menu === null && $this->menu !== null) {
            $this->menu->setRole(null);
        }

        // set the owning side of the relation if necessary
        if ($menu !== null && $menu->getRole() !== $this) {
            $menu->setRole($this);
        }

        $this->menu = $menu;

        return $this;
    }

    public function getKeycloakRoleId(): ?string {
        return $this->keycloakRoleId;
    }

    public function setKeycloakRoleId(?string $keycloakRoleId): self {
        $this->keycloakRoleId = $keycloakRoleId;

        return $this;
    }

    /**
     * @return Collection|UserRole[]
     */
    public function getUserRoles(): Collection {
        return $this->userRoles;
    }

    public function addUserRole(UserRole $userRole): self {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->setRole($this);
        }

        return $this;
    }

    public function removeUserRole(UserRole $userRole): self {
        if ($this->userRoles->removeElement($userRole)) {
            // set the owning side to null (unless already changed)
            if ($userRole->getRole() === $this) {
                $userRole->setRole(null);
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
     * @return Collection|RoleGrupo[]
     */
    public function getRolesGrupo(): Collection
    {
        return $this->rolesGrupo;
    }

    public function addRolesGrupo(RoleGrupo $rolesGrupo): self
    {
        if (!$this->rolesGrupo->contains($rolesGrupo)) {
            $this->rolesGrupo[] = $rolesGrupo;
            $rolesGrupo->setRole($this);
        }

        return $this;
    }

    public function removeRolesGrupo(RoleGrupo $rolesGrupo): self
    {
        if ($this->rolesGrupo->removeElement($rolesGrupo)) {
            // set the owning side to null (unless already changed)
            if ($rolesGrupo->getRole() === $this) {
                $rolesGrupo->setRole(null);
            }
        }

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

}
