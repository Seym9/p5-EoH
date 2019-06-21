<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticlesCommentsRepository")
 * @Entity
 * @Table(name="p5_articlescomments")
 */
class ArticlesComments
{
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $report;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Articles", inversedBy="articlesComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="articlesComments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleCommentReport", mappedBy="comment",cascade={"persist", "remove"})
     */
    private $articleCommentReports;

    public function __construct()
    {
        $this->articleCommentReports = new ArrayCollection();
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

    public function getArticle(): ?Articles
    {
        return $this->article;
    }

    public function setArticle(?Articles $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getAuthor(): ?users
    {
        return $this->author;
    }

    public function setAuthor(?users $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|ArticleCommentReport[]
     */
    public function getArticleCommentReports(): Collection
    {
        return $this->articleCommentReports;
    }

    public function addArticleCommentReport(ArticleCommentReport $articleCommentReport): self
    {
        if (!$this->articleCommentReports->contains($articleCommentReport)) {
            $this->articleCommentReports[] = $articleCommentReport;
            $articleCommentReport->setComment($this);
        }

        return $this;
    }

    public function removeArticleCommentReport(ArticleCommentReport $articleCommentReport): self
    {
        if ($this->articleCommentReports->contains($articleCommentReport)) {
            $this->articleCommentReports->removeElement($articleCommentReport);
            // set the owning side to null (unless already changed)
            if ($articleCommentReport->getComment() === $this) {
                $articleCommentReport->setComment(null);
            }
        }

        return $this;
    }

    public function isReportedByUser(Users $user): bool {
        foreach ($this->articleCommentReports as $report){
            if ($report->getUser() === $user) return true;
        }
        return false;
    }
}
