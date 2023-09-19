<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column]
    private ?int $totalItems = null;

    #[ORM\Column]
    private ?int $orderPrice = null;

    #[ORM\Column]
    private ?int $fkUserId = null;

    #[ORM\Column]
    private ?int $orderStatusCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): static
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getTotalItems(): ?int
    {
        return $this->totalItems;
    }

    public function setTotalItems(int $totalItems): static
    {
        $this->totalItems = $totalItems;

        return $this;
    }

    public function getOrderPrice(): ?int
    {
        return $this->orderPrice;
    }

    public function setOrderPrice(int $orderPrice): static
    {
        $this->orderPrice = $orderPrice;

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

    public function getOrderStatusCode(): ?int
    {
        return $this->orderStatusCode;
    }

    public function setOrderStatusCode(int $orderStatusCode): static
    {
        $this->orderStatusCode = $orderStatusCode;

        return $this;
    }
}
