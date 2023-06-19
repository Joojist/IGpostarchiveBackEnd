<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="post")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $path;

    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="post")
     */
    private $media;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes;

    /**
     * @ORM\Column(type="integer")
     */
    private $creatorId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $updaterId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $igCreatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="string")
     */
    private $caption;

    /**
     * @ORM\Column(type="array")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="IGUser", inversedBy="posts")
     * @ORM\JoinColumn(name="ig_user_id", referencedColumnName="id")
     */
    private $igUser1;
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", cascade={"persist", "remove"})
     */
    private $comments;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $userComment;

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function addMedia(Media $media): self
    {
        if (!$this->media->contains($media)) {
            $this->media[] = $media;
            $media->setPost($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        $this->media->removeElement($media);

        return $this;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        $this->comments->removeElement($comment);

        return $this;
    }

    public function getIgUser(): ?IGUser
    {
        return $this->igUser1;
    }

    public function setIgUser(?IGUser $igUser1): self
    {
        $this->igUser1 = $igUser1;
        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath($path): self
    {
        $this->path = $path;
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

    public function getUpdaterId(): int
    {
        return $this->updaterId;
    }

    public function setUpdaterId(?int $updaterId): self
{
    $this->updaterId = $updaterId ?? null;
    return $this;
}


    public function getIgCreatedAt(): DateTimeInterface
    {
        return $this->igCreatedAt;
    }

    public function setIgCreatedAt(DateTimeInterface $igCreatedAt): self
    {
        $this->igCreatedAt = $igCreatedAt;
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

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
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
    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }
    public function getUserComment(): ?string
    {
        return $this->userComment;
    }

    public function setUserComment(?string $userComment): self
    {
        $this->userComment = $userComment;
        return $this;
    }
}
