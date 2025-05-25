<?php

namespace MovieStar\Model;

use DateTime;
use MovieStar\Config\DateConfig;

class UserModel
{
    public const TABLE = "users";

    private ?int $id = null;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $password;
    private string $image = "";
    private string $bio = "";
    private string $token = "";
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __toString(): string
    {
        return "User [ID: {$this->id}, Email: {$this->email}, Name: {$this->firstName} {$this->lastName}]";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFirstName(string $firstName): void
    {
        if (empty($firstName)) {
            throw new \InvalidArgumentException("The first name cannot be empty");
        }

        $this->firstName = $firstName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setLastName(string $lastName): void
    {
        if (empty($lastName)) {
            throw new \InvalidArgumentException("The last name cannot be empty");
        }

        $this->lastName = $lastName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setEmail(string $email): void
    {
        if (empty($email)) {
            throw new \InvalidArgumentException("The email cannot be empty");
        }

        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): void
    {
        if (empty($password)) {
            throw new \InvalidArgumentException("The password cannot be empty");
        }

        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setImage(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("The user image name cannot be empty");
        }

        $this->image = $name;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setBio(string $bio): void
    {
        if (empty($bio)) {
            throw new \InvalidArgumentException("The bio cannot be empty");
        }

        $this->bio = $bio;
    }

    public function getBio(): string
    {
        return $this->bio;
    }

    public function setToken(string $token): void
    {
        if (empty($token)) {
            throw new \InvalidArgumentException("The token cannot be empty");
        }

        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
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