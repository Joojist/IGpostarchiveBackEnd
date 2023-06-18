<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "text")]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: "Post", inversedBy: "comments")]
    #[ORM\JoinColumn(name: "post_id", referencedColumnName: "id")]
    private ?Posts $post = null;

    #[ORM\ManyToOne(targetEntity: "Comments", inversedBy: "replies")]
    #[ORM\JoinColumn(name: "parent_id", referencedColumnName: "id", nullable: true)]
    private ?Comments $parent = null;

    #[ORM\OneToMany(targetEntity: "Comments", mappedBy: "parent")]
    private $replies;

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

    public function setContent(string $content): self
    {
        $this->content = $content;
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

    public function getParent(): ?Comments
    {
        return $this->parent;
    }

    public function setParent(?Comments $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    public function getReplies()
    {
        return $this->replies;
    }

    public function addReply(Comments $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setParent($this);
        }

        return $this;
    }

    public function removeReply(Comments $reply): self
    {
        $this->replies->removeElement($reply);

        return $this;
    }
}
