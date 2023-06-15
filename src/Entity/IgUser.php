<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="IgUser")
 */
class IgUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="ig_id", type="string", length=50)
     */
    private $igId;

    /**
     * @ORM\Column(name="username", type="string", length=50)
     */
    private $username;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="deleted_at", type="datetime")
     */
    private $deletedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getIgId(): string
    {
        return $this->igId;
    }

    public function setIgId(string $igId): self
    {
        $this->igId = $igId;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeletedAt(): DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
