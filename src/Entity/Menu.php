<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuRepository")
 */
class Menu {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="app", type="string", length=15, nullable=true)
     */
    private $app;

    /**
     * @var int|null
     *
     * @ORM\Column(name="active", type="smallint", nullable=true, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var int|null
     *
     * @ORM\Column(name="created_by", type="integer", nullable=true)
     */
    private $createdBy;

    /**
     * @var int|null
     *
     * @ORM\Column(name="updated_by", type="integer", nullable=true)
     */
    private $updatedBy;

    /**
     * @var int|null
     *
     * @ORM\Column(name="deleted_by", type="integer", nullable=true)
     */
    private $deletedBy;

    /**
     * @ORM\OneToMany(targetEntity=Menuitem::class, mappedBy="menuId")
     */
    private $items;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEliminacion;

    public function __construct() {
        $this->items = new ArrayCollection();
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getApp(): ?string {
        return $this->app;
    }

    public function setApp(?string $app): self {
        $this->app = $app;

        return $this;
    }

    public function getActive(): ?int {
        return $this->active;
    }

    public function setActive(?int $active): self {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getCreatedBy(): ?int {
        return $this->createdBy;
    }

    public function setCreatedBy(?int $createdBy): self {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?int {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?int $updatedBy): self {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getDeletedBy(): ?int {
        return $this->deletedBy;
    }

    public function setDeletedBy(?int $deletedBy): self {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    /**
     * @return Collection|Menuitem[]
     */
    public function getItems(): Collection {
        return $this->items;
    }

    public function addItem(Menuitem $item): self {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setMenuId($this);
        }

        return $this;
    }

    public function removeItem(Menuitem $item): self {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getMenuId() === $this) {
                $item->setMenuId(null);
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

}
