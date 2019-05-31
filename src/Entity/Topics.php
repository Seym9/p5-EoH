<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicsRepository")
 * @Entity
 * @Table(name="p5_Topics")
 */
class Topics
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $report;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumCategories", inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="topics", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TopicsComments", mappedBy="topic", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $topicsComments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TopicLike", mappedBy="topic")
     */
    private $topicLikes;

    public function __construct()
    {
        $this->topicsComments = new ArrayCollection();
        $this->topicLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReport(): ?int
    {
        return $this->report;
    }

    public function setReport(?int $report): self
    {
        $this->report = $report;

        return $this;
    }

    public function getCategory(): ?ForumCategories
    {
        return $this->category;
    }

    public function setCategory(?ForumCategories $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?Users
    {
        return $this->author;
    }

    public function setAuthor(?Users $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|TopicsComments[]
     */
    public function getTopicsComments(): Collection
    {
        return $this->topicsComments;
    }

    public function addTopicsComment(TopicsComments $topicsComment): self
    {
        if (!$this->topicsComments->contains($topicsComment)) {
            $this->topicsComments[] = $topicsComment;
            $topicsComment->setTopic($this);
        }

        return $this;
    }

    public function removeTopicsComment(TopicsComments $topicsComment): self
    {
        if ($this->topicsComments->contains($topicsComment)) {
            $this->topicsComments->removeElement($topicsComment);
            // set the owning side to null (unless already changed)
            if ($topicsComment->getTopic() === $this) {
                $topicsComment->setTopic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TopicLike[]
     */
    public function getTopicLikes(): Collection
    {
        return $this->topicLikes;
    }

    public function addTopicLike(TopicLike $topicLike): self
    {
        if (!$this->topicLikes->contains($topicLike)) {
            $this->topicLikes[] = $topicLike;
            $topicLike->setTopic($this);
        }

        return $this;
    }

    public function removeTopicLike(TopicLike $topicLike): self
    {
        if ($this->topicLikes->contains($topicLike)) {
            $this->topicLikes->removeElement($topicLike);
            // set the owning side to null (unless already changed)
            if ($topicLike->getTopic() === $this) {
                $topicLike->setTopic(null);
            }
        }

        return $this;
    }


    /**
     * Allow to know if the Topic is liked by a user
     *
     * @param Users $user
     * @return bool
     */
    public function isLikeByUser(Users $user): bool {
        foreach ($this->topicLikes as $like){
            if ($like->getAuthor() === $user) return true;
        }
        return false;
    }
}
