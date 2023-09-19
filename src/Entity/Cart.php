<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(mappedBy: 'ProductId', targetEntity: Product::class)]
    private ?int $fkProductId = null;

    #[ORM\Column]
    private ?int $fkUserId = null;

    #[ORM\Column]
    private ?int $fkShippingId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $promocode = null;

    #[ORM\Column]
    private ?int $fkCartItem = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFkProductId(): ?int
    {
        return $this->fkProductId;
    }

    public function setFkProductId(int $fkProductId): static
    {
        $this->fkProductId = $fkProductId;

        return $this;
    }

    public function getFkUserId(): ?int
    {
        return $this->fkUserId;
    }

    public function setFkUserId(int $fkUserId): static
    {
        $this->fkUserId = $fkUserId;

        return $this;
    }

    public function getFkShippingId(): ?int
    {
        return $this->fkShippingId;
    }

    public function setFkShippingId(int $fkShippingId): static
    {
        $this->fkShippingId = $fkShippingId;

        return $this;
    }

    public function getPromocode(): ?string
    {
        return $this->promocode;
    }

    public function setPromocode(?string $promocode): static
    {
        $this->promocode = $promocode;

        return $this;
    }

    public function getFkCartItem(): ?int
    {
        return $this->fkCartItem;
    }

    public function setFkCartItem(int $fkCartItem): static
    {
        $this->fkCartItem = $fkCartItem;

        return $this;
    }
}
