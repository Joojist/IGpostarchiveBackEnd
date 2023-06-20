<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Comment")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @ORM\OneToMany(targetEntity="Reply", mappedBy="comment", cascade={"persist", "remove"})
     */
    private $replies;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $author;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Reply[]
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(Reply $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $reply->setComment($this);
            $this->replies->add($reply);
        }

        return $this;
    }

    public function removeReply(Reply $reply): self
    {
        if ($this->replies->contains($reply)) {
            $this->replies->removeElement($reply);
            // set the owning side to null (unless already changed)
            if ($reply->getComment() === $this) {
                $reply->setComment(null);
            }
        }

        return $this;
    }
}
