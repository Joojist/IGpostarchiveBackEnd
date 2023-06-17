<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="media")
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="media")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    // ...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    // ...
}