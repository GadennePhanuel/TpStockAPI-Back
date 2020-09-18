<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @ORM\OneToMany(targetEntity=Belong::class, mappedBy="article")
     */
    private $belongs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="article")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->belongs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * @return Collection|Belong[]
     */
    public function getBelongs(): Collection
    {
        return $this->belongs;
    }

    public function addBelong(Belong $belong): self
    {
        if (!$this->belongs->contains($belong)) {
            $this->belongs[] = $belong;
            $belong->setArticle($this);
        }

        return $this;
    }

    public function removeBelong(Belong $belong): self
    {
        if ($this->belongs->contains($belong)) {
            $this->belongs->removeElement($belong);
            // set the owning side to null (unless already changed)
            if ($belong->getArticle() === $this) {
                $belong->setArticle(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
