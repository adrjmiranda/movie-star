<?php

namespace MovieStar\Model;

use DateTime;
use MovieStar\Config\DateConfig;

class CategoryModel
{
    public const TABLE = "categories";

    private ?int $id = null;
    private string $name;
    private string $image = "";
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("The category name cannot be empty");
        }

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setImage(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("The category image name cannot be empty");
        }

        $this->image = $name;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setCreatedAt(?DateTime $createdAt = null): void
    {
        $this->createdAt = $createdAt ?? new DateTime("now");
    }

    public function getCreatedAt(string $format = DateConfig::FORMAT): string
    {
        return $this->createdAt->format($format);
    }

    public function getCreatedAtObject(): DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt = null): void
    {
        $this->updatedAt = $updatedAt ?? new DateTime("now");
    }

    public function getUpdatedAt(string $format = DateConfig::FORMAT): string
    {
        return $this->updatedAt->format($format);
    }

    public function getUpdatedAtObject(): DateTime
    {
        return $this->updatedAt;
    }
}