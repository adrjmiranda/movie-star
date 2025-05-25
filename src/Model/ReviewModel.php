<?php

namespace MovieStar\Model;

use DateTime;
use MovieStar\Config\DateConfig;

class ReviewModel
{
    public const TABLE = "reviews";

    public const ALLOWED_RATINGS = [
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10
    ];

    private ?int $id = null;
    private string $comment;
    private int $rating;
    private int $movieId;
    private int $userId;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setComment(string $comment): void
    {
        if (empty($comment)) {
            throw new \InvalidArgumentException("The comment cannot be empty");
        }

        $this->comment = $comment;
    }

    public function getComment(): string
    {


        return $this->comment;
    }

    public function setRating(int $rating): void
    {
        if (!in_array($rating, self::ALLOWED_RATINGS)) {
            throw new \InvalidArgumentException("Only ratings from 1 to 10 are allowed");
        }

        $this->rating = $rating;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setMovieId(int $movieId): void
    {
        if ($movieId < 1) {
            throw new \InvalidArgumentException("Movie id must be greater than 1");
        }

        $this->movieId = $movieId;
    }

    public function getMovieId(): int
    {
        return $this->movieId;
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