<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 * @UniqueEntity("label", message="ce nom de stock est déjà utilisé")
 * @ApiResource(
 *     attributes={
        "order"={"label":"ASC"}
 *     },
 *     normalizationContext={
 *          "groups"={"stocks_read"}
 *     }
 * )
 * @ApiFilter(
 *     SearchFilter::class, properties={"label":"partial"}
 * )
 * @ApiFilter(
 *     OrderFilter::class, properties={"label":"asc"}
 * )
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"articles_read", "belong_read", "stocks_read", "user_read"})
     * @Assert\NotBlank(message="le nom du stock est obligatoire")
     * @Assert\Type(type="string", message="le nom du stock doit être une chaine de caractére")
     * @Assert\Length(min="3", max="50", minMessage="Le nom du stock doit faire entre 3 et 50 caractéres", maxMessage="Le nom du stock doit faire entre 3 et 50 caractéres")
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=Belong::class, mappedBy="stock")
     * @Groups({"stocks_read", "user_read"})
     */
    private $belongs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stock")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"belong_read", "stocks_read"})
     * @Assert\NotBlank(message="l'utilisateur est obligatoire")
     */
    private $user;

    public function __construct()
    {
        $this->belongs = new ArrayCollection();
    }

    /**
     * Permet de retourner le nombre total d'article du stock
     * @Groups({"stocks_read"})
     * @return int
     */
    public function getTotalArticleCurrentStock(): int
    {
        return count($this->belongs->toArray());
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
            $belong->setStock($this);
        }

        return $this;
    }

    public function removeBelong(Belong $belong): self
    {
        if ($this->belongs->contains($belong)) {
            $this->belongs->removeElement($belong);
            // set the owning side to null (unless already changed)
            if ($belong->getStock() === $this) {
                $belong->setStock(null);
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
