<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicReportRepository")
 */
class TopicReport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Topics", inversedBy="topicReports")
     */
    private $topics;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="topicReport")
     */
    private $users;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopic(): ?Topics
    {
        return $this->topics;
    }

    public function setTopic(?Topics $topic): self
    {
        $this->topics = $topic;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->users;
    }

    public function setUser(?Users $user): self
    {
        $this->users = $user;

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
}
