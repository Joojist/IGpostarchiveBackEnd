<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string")]
    private string $path;

    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: "post", cascade: ["persist"])]
    private Collection $media;

    #[ORM\Column(type: "integer")]
    private int $likes;

    #[ORM\Column(type: "integer")]
    private int $creatorId;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $updaterId;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $igCreatedAt;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $updatedAt;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $deletedAt;

    #[ORM\Column(type: "string")]
    private string $caption;

    #[ORM\Column(type: "array")]
    private array $tags;

    #[ORM\ManyToOne(targetEntity: IgUsers::class, cascade: ["persist"])]
    #[ORM\JoinColumn(name: "ig_user_id", referencedColumnName: "id")]
    private ?IgUsers $igUser;

    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: "post", cascade: ["persist", "remove"])]
    private Collection $comments;

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

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

    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedia(Medias $media): self
    {
        if (!$this->media->contains($media)) {
            $this->media[] = $media;
            $media->setPost($this);
        }
        return $this;
    }

    public function removeMedia(Medias $media): self
    {
        $this->media->removeElement($media);
        return $this;
    }

    public function getLikes(): int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;
        return $this;
    }

    public function getCreatorId(): int
    {
        return $this->creatorId;
    }

    public function setCreatorId(int $creatorId): self
    {
        $this->creatorId = $creatorId;
        return $this;
    }

    public function getUpdaterId(): ?int
    {
        return $this->updaterId;
    }

    public function setUpdaterId(?int $updaterId): self
    {
        $this->updaterId = $updaterId;
        return $this;
    }

    public function getIgCreatedAt(): \DateTimeInterface
    {
        return $this->igCreatedAt;
    }

    public function setIgCreatedAt(\DateTimeInterface $igCreatedAt): self
    {
        $this->igCreatedAt = $igCreatedAt;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function getCaption(): string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): self
    {
        $this->caption = $caption;
        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    public function getIgUser(): ?IgUsers
    {
        return $this->igUser;
    }

    public function setIgUser(?IgUsers $igUser): self
    {
        $this->igUser = $igUser;
        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }
        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        $this->comments->removeElement($comment);
        return $this;
    }
}
