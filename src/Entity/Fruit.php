<?php

namespace App\Entity;

use App\Repository\FruitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruitRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Fruit
{
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

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
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
}
