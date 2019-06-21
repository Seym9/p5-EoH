<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicsCommentsRepository")
 * @Entity
 * @Table(name="p5_topicscomments")
 */
class TopicsComments {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Topics", inversedBy="topicsComments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $topic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="topicsComments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TopicCommentReport", mappedBy="comment",cascade={"persist", "remove"})
     */
    private $topicCommentReports;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $report;

    public function __construct() {
        $this->topicCommentReports = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(string $content): self {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReport(): ?int {
        return $this->report;
    }

    public function setReport(?int $report): self {
        $this->report = $report;

        return $this;
    }

    public function getTopic(): ?Topics {
        return $this->topic;
    }

    public function setTopic(?Topics $topic): self {
        $this->topic = $topic;

        return $this;
    }

    public function getAuthor(): ?Users {
        return $this->author;
    }

    public function setAuthor(?Users $author): self {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|TopicCommentReport[]
     */
    public function getTopicCommentReports(): Collection {
        return $this->topicCommentReports;
    }

    public function addTopicCommentReport(TopicCommentReport $topicCommentReport): self {
        if (!$this->topicCommentReports->contains($topicCommentReport)) {
            $this->topicCommentReports[] = $topicCommentReport;
            $topicCommentReport->setComment($this);
        }

        return $this;
    }

    public function removeTopicCommentReport(TopicCommentReport $topicCommentReport): self {
        if ($this->topicCommentReports->contains($topicCommentReport)) {
            $this->topicCommentReports->removeElement($topicCommentReport);
            // set the owning side to null (unless already changed)
            if ($topicCommentReport->getComment() === $this) {
                $topicCommentReport->setComment(null);
            }
        }

        return $this;
    }

    public function isReportedByUser(Users $user): bool {
        foreach ($this->topicCommentReports as $report) {
            if ($report->getUser() === $user) return true;
        }
        return false;
    }
}
