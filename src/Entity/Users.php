<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @Entity
 * @Table(name="p5_Users")
 * @UniqueEntity(
 *     fields = {"email"},
 *     message = "L'adresse email que vous avez indiquée est déjà utilisé"
 * )
 */
class Users implements UserInterface
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8" , minMessage="le mdp c'est 8 char mini")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $report;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tips", mappedBy="author")
     */
    private $tips;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Topics", mappedBy="author")
     * @ORM\JoinColumn(nullable=true)
     */
    private $topics;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Articles", mappedBy="author")
     * @ORM\JoinColumn(nullable=true)
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticlesComments", mappedBy="author")
     * @ORM\JoinColumn(nullable=true)
     */
    private $articlesComments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TopicsComments", mappedBy="author")
     * @ORM\JoinColumn(nullable=true)
     */
    private $topicsComments;

    /**
     *@Assert\EqualTo(propertyPath="password", message="Les deux mot de passe ne sont pas identiques")
     */
    public $confirm_password;
    /**
     * @ORM\OneToOne(targetEntity="ImageUser", cascade={"persist", "remove"})
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TopicLike", mappedBy="user", cascade={"persist", "remove"})
     */
    private $topicLikes;

    public function __construct()
    {
        $this->tips = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->articlesComments = new ArrayCollection();
        $this->topicsComments = new ArrayCollection();
        $this->topicLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    /**
     * @return Collection|Tips[]
     */
    public function getTips(): Collection
    {
        return $this->tips;
    }

    public function addTip(Tips $tip): self
    {
        if (!$this->tips->contains($tip)) {
            $this->tips[] = $tip;
            $tip->setAuthor($this);
        }

        return $this;
    }

    public function removeTip(Tips $tip): self
    {
        if ($this->tips->contains($tip)) {
            $this->tips->removeElement($tip);
            // set the owning side to null (unless already changed)
            if ($tip->getAuthor() === $this) {
                $tip->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Topics[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topics $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setAuthor($this);
        }

        return $this;
    }

    public function removeTopic(Topics $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
            // set the owning side to null (unless already changed)
            if ($topic->getAuthor() === $this) {
                $topic->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

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
            $articlesComment->setAuthor($this);
        }

        return $this;
    }

    public function removeArticlesComment(ArticlesComments $articlesComment): self
    {
        if ($this->articlesComments->contains($articlesComment)) {
            $this->articlesComments->removeElement($articlesComment);
            // set the owning side to null (unless already changed)
            if ($articlesComment->getAuthor() === $this) {
                $articlesComment->setAuthor(null);
            }
        }

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
            $topicsComment->setAuthor($this);
        }

        return $this;
    }

    public function removeTopicsComment(TopicsComments $topicsComment): self
    {
        if ($this->topicsComments->contains($topicsComment)) {
            $this->topicsComments->removeElement($topicsComment);
            // set the owning side to null (unless already changed)
            if ($topicsComment->getAuthor() === $this) {
                $topicsComment->setAuthor(null);
            }
        }

        return $this;
    }

//    /**
//     * Returns the roles granted to the user.
//     *
//     *     public function getRoles()
//     *     {
//     *         return ['ROLE_USER'];
//     *     }
//     *
//     * Alternatively, the roles might be stored on a ``roles`` property,
//     * and populated in any number of different ways when the user object
//     * is created.
//     *
//     * @return (Role|string)[] The user roles
//     */
//    public function getRoles() {
//
//        return['ROLE_USER'];
//    }
    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    public function getRoles(): array
    {
        $role = $this->roles;
        if (empty($role)) {
            $role[] = 'ROLE_USER';
        }
        return array_unique($role);
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt() {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials() {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return mixed
     */
    public function getImage(): ?ImageUser {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void {
        $this->image = $image;
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
            $topicLike->setUser($this);
        }

        return $this;
    }

    public function removeTopicLike(TopicLike $topicLike): self
    {
        if ($this->topicLikes->contains($topicLike)) {
            $this->topicLikes->removeElement($topicLike);
            // set the owning side to null (unless already changed)
            if ($topicLike->getUser() === $this) {
                $topicLike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @var string le token qui servira lors de l'oubli de mot de passe
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;

    /**
     * @return string
     */
    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

    public function setRole(array $role): self
    {
        $this->roles = $role;
        return $this;
    }

}
