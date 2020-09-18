<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("username", message="ce nom d'utilisateur est déjà utilisé")
 * @ApiResource(
 *      order={"lastName":"asc", "firstName":"asc"},
 *     normalizationContext={"groups"={"user_read"}}
 * )
 * @ApiFilter(
 *     SearchFilter::class, properties={"firstName":"partial", "lastName":"partial", "username":"partial"}
 * )
 * @ApiFilter(
 *     OrderFilter::class, properties={"lastName":"asc", "firstName":"asc"}
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user_read"})
     * @Assert\NotBlank(message="le nom d'utilisateur est obligatoire")
     * @Assert\Length(min="3", max="50", minMessage="le nom d'utilisateur doit faire entre 3 et 50 caractéres", maxMessage="le nom d'utilisateur doit faire entre 3 et 50 caractéres")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="le mot de passe est obligatoire")
     * @Assert\Length(min="8", max="255", minMessage="le password doit faire entre 8 et 255 caractéres", maxMessage="le paswword doit faire entre 8 et 255 caractéres")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"articles_read", "belong_read", "stocks_read"})
     * @Groups({"user_read"})
     * @Assert\NotBlank(message="le prénom est obligatoire")
     * @Assert\Length(min="3", max="50", minMessage="le prénom doit faire entre 3 et 50 caractéres", maxMessage="le prénom doit faire entre 3 et 50 caractéres")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"articles_read", "belong_read", "stocks_read"})
     * @Groups({"user_read"})
     * @Assert\NotBlank(message="le nom de famille est obligatoire")
     * @Assert\Length(min="3", max="50", minMessage="le nom doit faire entre 3 et 50 caractéres", maxMessage="le nom doit faire entre 3 et 50 caractéres")
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="user")
     * @Groups({"user_read"})
     */
    private $article;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="user")
     * @Groups({"user_read"})
     */
    private $stock;

    public function __construct()
    {
        $this->article = new ArrayCollection();
        $this->stock = new ArrayCollection();
    }


    /**
     * Permet de récupérer le nombre total de stock que possède cette utilisateur
     * @Groups({"user_read"})
     * @return int
     */
    public function getTotalStock(): int
    {
        return count($this->stock->toArray());
    }

    /**
     * Permet de récupérer le nombre total d'articles que possède cet utilisateur
     * @Groups({"user_read"})
     * @return int
     */
    public function getTotalArticle(): int
    {
        return count($this->article->toArray());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->contains($article)) {
            $this->article->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Stock[]
     */
    public function getStock(): Collection
    {
        return $this->stock;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stock->contains($stock)) {
            $this->stock[] = $stock;
            $stock->setUser($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stock->contains($stock)) {
            $this->stock->removeElement($stock);
            // set the owning side to null (unless already changed)
            if ($stock->getUser() === $this) {
                $stock->setUser(null);
            }
        }

        return $this;
    }
}
