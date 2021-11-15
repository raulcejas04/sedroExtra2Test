<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RolemenuRepository::class)
 */
class Rolemenu {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Menuitem::class, inversedBy="roles")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     */
    private $menuId;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="menus")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMenuId(): ?Menuitem
    {
        return $this->menuId;
    }

    public function setMenuId(?Menuitem $menuId): self
    {
        $this->menuId = $menuId;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

}
