<?php

namespace App\Entity;

use App\Repository\ProductInCartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductInCartRepository::class)]
class ProductInCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $fkProductId = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private $fkUserId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="productsInCart")
     * @ORM\JoinColumn(name="fk_user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productInCarts")
     * @ORM\JoinColumn(name="fk_product_id", referencedColumnName="id")
     */
    private $product;


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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getFkUserId(): ?int
    {
        return $this->fkUserId;
    }

    public function setFkUserId(?int $fkUserId): static
    {
        $this->fkUserId = $fkUserId;

        return $this;
    }



    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
