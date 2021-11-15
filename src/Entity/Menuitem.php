<?php

namespace App\Entity;

use App\Repository\MenuitemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Menuitem
 *
 * @ORM\Entity(repositoryClass=MenuitemRepository::class)
 */
class Menuitem {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="menuitem_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Menu::class, inversedBy="items")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     */
    private $menuId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="rolecompany", type="string", length=20, nullable=true)
     */
    private $rolecompany;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nametree", type="string", length=50, nullable=true)
     */
    private $nametree;

    /**
     * @var string|null
     *
     * @ORM\Column(name="icon", type="string", length=100, nullable=true)
     */
    private $icon;

    /**
     * @var string|null
     *
     * @ORM\Column(name="link", type="string", length=50, nullable=true)
     */
    private $link;

    /**
     * @var int|null
     *
     * @ORM\Column(name="active", type="smallint", nullable=true, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="orderlist1", type="integer", nullable=true)
     */
    private $orderlist1 = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="orderlist", type="integer", nullable=true)
     */
    private $orderlist;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type_id", type="string", length=1, nullable=true)
     */
    private $typeId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="availablesel", type="smallint", nullable=true, options={"default"="1"})
     */
    private $availablesel = '1';

    /**
     * @var string|null
     *
     * @ORM\Column(name="module", type="string", length=80, nullable=true)
     */
    private $module;

    /**
     * @var string|null
     *
     * @ORM\Column(name="action", type="string", length=80, nullable=true)
     */
    private $action;

    /**
     * @var int
     *
     * @ORM\Column(name="divider", type="smallint", nullable=false)
     */
    private $divider = '0';

    /**
     * @ORM\ManyToOne(targetEntity=Menuitem::class, inversedBy="menuitems")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Rolemenu::class, mappedBy="menuId")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity=Menuitem::class, mappedBy="parent")
     */
    private $menuitems;

    public function __construct() {
        $this->roles = new ArrayCollection();
        $this->menuitems = new ArrayCollection();
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getMenuId(): ?int {
        return $this->menuId;
    }

    public function setMenuId(?int $menuId): self {
        $this->menuId = $menuId;

        return $this;
    }

    public function getRolecompany(): ?string {
        return $this->rolecompany;
    }

    public function setRolecompany(?string $rolecompany): self {
        $this->rolecompany = $rolecompany;

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getNametree(): ?string {
        return $this->nametree;
    }

    public function setNametree(?string $nametree): self {
        $this->nametree = $nametree;

        return $this;
    }

    public function getIcon(): ?string {
        return $this->icon;
    }

    public function setIcon(?string $icon): self {
        $this->icon = $icon;

        return $this;
    }

    public function getLink(): ?string {
        return $this->link;
    }

    public function setLink(?string $link): self {
        $this->link = $link;

        return $this;
    }

    public function getActive(): ?int {
        return $this->active;
    }

    public function setActive(?int $active): self {
        $this->active = $active;

        return $this;
    }

    public function getOrderlist1(): ?int {
        return $this->orderlist1;
    }

    public function setOrderlist1(?int $orderlist1): self {
        $this->orderlist1 = $orderlist1;

        return $this;
    }

    public function getOrderlist(): ?int {
        return $this->orderlist;
    }

    public function setOrderlist(?int $orderlist): self {
        $this->orderlist = $orderlist;

        return $this;
    }

    public function getTypeId(): ?string {
        return $this->typeId;
    }

    public function setTypeId(?string $typeId): self {
        $this->typeId = $typeId;

        return $this;
    }

    public function getAvailablesel(): ?int {
        return $this->availablesel;
    }

    public function setAvailablesel(?int $availablesel): self {
        $this->availablesel = $availablesel;

        return $this;
    }

    public function getModule(): ?string {
        return $this->module;
    }

    public function setModule(?string $module): self {
        $this->module = $module;

        return $this;
    }

    public function getAction(): ?string {
        return $this->action;
    }

    public function setAction(?string $action): self {
        $this->action = $action;

        return $this;
    }

    public function getDivider(): ?int {
        return $this->divider;
    }

    public function setDivider(int $divider): self {
        $this->divider = $divider;

        return $this;
    }

    public function getParent(): ?self {
        return $this->parent;
    }

    public function setParent(?self $parent): self {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|Rolemenu[]
     */
    public function getRoles(): Collection {
        return $this->roles;
    }

    public function addRole(Rolemenu $role): self {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->setMenuId($this);
        }

        return $this;
    }

    public function removeRole(Rolemenu $role): self {
        if ($this->roles->removeElement($role)) {
            // set the owning side to null (unless already changed)
            if ($role->getMenuId() === $this) {
                $role->setMenuId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Menuitem[]
     */
    public function getMenuitems(): Collection {
        return $this->menuitems;
    }

    public function addMenuitem(Menuitem $menuitem): self {
        if (!$this->menuitems->contains($menuitem)) {
            $this->menuitems[] = $menuitem;
            $menuitem->setParent($this);
        }

        return $this;
    }

    public function removeMenuitem(Menuitem $menuitem): self {
        if ($this->menuitems->removeElement($menuitem)) {
            // set the owning side to null (unless already changed)
            if ($menuitem->getParent() === $this) {
                $menuitem->setParent(null);
            }
        }

        return $this;
    }

}
