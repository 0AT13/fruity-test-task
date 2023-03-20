<?php

namespace App\Entity;

use App\Entity\Traits\PrePersistCreatedAtTrait;
use App\Repository\FruitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruitRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Fruit
{
    use PrePersistCreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $name;

    #[ORM\ManyToOne(inversedBy: 'fruits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genus $genus;

    #[ORM\ManyToOne(inversedBy: 'fruits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Family $family;

    #[ORM\ManyToOne(inversedBy: 'fruits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $frOrder = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private array $nutriotions = [];

    #[ORM\OneToMany(mappedBy: 'fruit', targetEntity: FavoriteFruit::class)]
    private Collection $favorite;

    public function __construct()
    {
        $this->favorite = new ArrayCollection();
    }

    public function getId(): int
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

    public function getGenus(): ?Genus
    {
        return $this->genus;
    }

    public function setGenus(Genus $genus): self
    {
        $this->genus = $genus;

        return $this;
    }

    public function getFamily(): ?Family
    {
        return $this->family;
    }

    public function setFamily(Family $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getFrOrder(): ?Order
    {
        return $this->frOrder;
    }

    public function setFrOrder(Order $frOrder): self
    {
        $this->frOrder = $frOrder;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getNutriotions(): ?array
    {
        return $this->nutriotions;
    }

    public function setNutriotions(array $nutriotions): self
    {
        $this->nutriotions = $nutriotions;

        return $this;
    }

    /**
     * @return Collection<int, FavoriteFruit>
     */
    public function getFavorites(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(FavoriteFruit $favoriteFruit): self
    {
        if (!$this->favorite->contains($favoriteFruit)) {
            $this->favorite->add($favoriteFruit);
            $favoriteFruit->setFruit($this);
        }

        return $this;
    }

    public function removeFavorite(FavoriteFruit $favoriteFruit): self
    {
        if ($this->favorite->removeElement($favoriteFruit)) {
            // set the owning side to null (unless already changed)
            if ($favoriteFruit->getFruit() === $this) {
                $favoriteFruit->setFruit(null);
            }
        }

        return $this;
    }
}
