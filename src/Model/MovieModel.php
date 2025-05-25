<?php

namespace MovieStar\Model;

use DateTime;
use MovieStar\Config\DateConfig;

class MovieModel
{
    public const TABLE = "movies";

    private ?int $id = null;
    private string $title;
    private string $description = "";
    private string $image = "";
    private string $trailer = "";
    private ?int $duration = null;
    private int $categoryId;
    private int $userId;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): void
    {
        if (empty($title)) {
            throw new \InvalidArgumentException("The movie title cannot be empty");
        }

        $this->title = $title;
    }

    public function getTtitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description): void
    {
        if (empty($description)) {
            throw new \InvalidArgumentException("The movie description cannot be empty");
        }

        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setImage(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("The movie image name cannot be empty");
        }

        $this->image = $name;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setTrailer(string $trailer): void
    {
        if (empty($trailer)) {
            throw new \InvalidArgumentException("The movie trailer cannot be empty");
        }

        $this->trailer = $trailer;
    }

    public function getTrailer(): string
    {
        return $this->trailer;
    }

    public function setDuration(int $duration): void
    {
        if ($duration < 1) {
            throw new \InvalidArgumentException("The duration of the film cannot be less than 1");
        }

        $this->duration = $duration;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setCategoryId(int $categoryId): void
    {
        if ($categoryId < 1) {
            throw new \InvalidArgumentException("Category id must be greater than 1");
        }

        $this->categoryId = $categoryId;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setUserId(int $userId): void
    {
        if ($userId < 1) {
            throw new \InvalidArgumentException("User id must be greater than 1");
        }

        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
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