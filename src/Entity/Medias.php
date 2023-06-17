<?php

namespace App\Entity;

use App\Repository\MediasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediasRepository::class)]
#[ORM\Table(name: "media")]
class Medias
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string")]
    private $path;

    #[ORM\ManyToOne(targetEntity: "Posts", inversedBy: "media")]
    #[ORM\JoinColumn(name: "post_id", referencedColumnName: "id")]
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

    public function getPost(): ?Posts
    {
        return $this->post;
    }

    public function setPost(?Posts $post): self
    {
        $this->post = $post;

        return $this;
    }

    // ...
}
