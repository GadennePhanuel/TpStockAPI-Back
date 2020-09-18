<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @UniqueEntity("ref", message="cette référence d'article est déjà utilisé")
 * @ApiResource(
 *  attributes={
        "order"={"price":"asc"},
 *     },
 *  normalizationContext={"groups"={"articles_read"}},
 * )
 * @ApiFilter(
 *      OrderFilter::class, properties={"label":"asc", "price":"asc"}
 * )
 * @ApiFilter(
 *     SearchFilter::class, properties={"label":"partial", "price":"exact", "ref":"partial"}
 * )
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
     * @Groups({"articles_read", "belong_read", "stocks_read", "user_read", "belongs_subresource"})
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Assert\Type(type="string", message="le type doit être une chaine de caractére")
     * @Assert\Length(min=3, max=50, minMessage="Le nom doit faire au moins 3 caractéres", maxMessage="le nom de l'article doit faire moins de 50 caractéres")
     */
    private $label;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"articles_read", "belong_read", "stocks_read", "user_read", "belongs_subresource"})
     * @Assert\NotBlank(message="Le prix est obligatoire")
     * @Assert\Type(type="numeric", message="le prix doit être numérique")
     * @Assert\Positive(message="le prix doit être positif")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"articles_read", "belong_read", "stocks_read", "user_read", "belongs_subresource"})
     * @Assert\NotBlank(message="La référence est obligatoire")
     * @Assert\Type(type="string", message="le type doit être une chaine de caractére")
     * @Assert\Length(min=3, max=50, minMessage="La ref doit faire au moins 3 caractéres", maxMessage="la ref de l'article doit faire moins de 50 caractéres")
     */
    private $ref;

    /**
     * @ORM\OneToMany(targetEntity=Belong::class, mappedBy="article")
     * @Groups({"articles_read"})
     */
    private $belongs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="article")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"articles_read"})
     * @Assert\NotBlank(message="l'utilisateur est obligatoire")
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
