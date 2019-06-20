<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticlesRepository")
 * @Entity
 * @Table(name="p5_articles")
 */
class Articles
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
     * @ORM\ManyToOne(targetEntity="App\Entity\ArticlesCategories", inversedBy="articles")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="articles", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticlesComments", mappedBy="article", cascade={"persist", "remove"})
     */
    private $articlesComments;

    /**
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist"}, cascade={"persist", "remove"})
     */
    private $image;

    public function __construct()
    {
        $this->articlesComments = new ArrayCollection();
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

    public function getCategory(): ?ArticlesCategories
    {
        return $this->category;
    }

    public function setCategory(?ArticlesCategories $category): self
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
     * @return Collection|ArticlesComments[]
     */
    public function getArticlesComments(): Collection
    {
        return $this->articlesComments;
    }

    public function addArticlesComment(ArticlesComments $articlesComment): self
    {
        if (!$this->articlesComments->contains($articlesComment)) {
            $this->articlesComments[] = $articlesComment;
            $articlesComment->setArticle($this);
        }
        return $this;
    }

    public function removeArticlesComment(ArticlesComments $articlesComment): self
    {
        if ($this->articlesComments->contains($articlesComment)) {
            $this->articlesComments->removeElement($articlesComment);
            // set the owning side to null (unless already changed)
            if ($articlesComment->getArticle() === $this) {
                $articlesComment->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage(): ?Image {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void {
        $this->image = $image;
    }
}
