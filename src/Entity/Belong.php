<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BelongRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BelongRepository::class)
 * @ApiResource(
 *      attributes={
        "order"={"qty":"DESC"}
 *      },
 *     normalizationContext={"groups"={"belong_read"}}
 * )
 */
class Belong
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="belongs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"belong_read", "stocks_read", "user_read"})
     * @Assert\NotBlank(message="l'article est obligatoire")
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity=Stock::class, inversedBy="belongs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"articles_read", "belong_read"})
     * @Assert\NotBlank(message="le stock est obligatoire")
     *
     */
    private $stock;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"articles_read"})
     * @Groups({"belong_read", "user_read"})
     * @Assert\NotBlank(message="la quantité est obligatoire")
     * @Assert\PositiveOrZero(message="la quantité doit être au minimum de 0")
     */
    private $qty;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }
}
