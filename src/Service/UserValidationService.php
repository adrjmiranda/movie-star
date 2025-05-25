<?php

namespace MovieStar\Service;

use Respect\Validation\Validator as v;

class UserValidationService
{
    private const PASS_MIN_LENGTH = 8;
    private const PASS_MAX_LENGTH = 100;
    private const FIRST_NAME_MIN_LENGTH = 2;
    private const FIRST_NAME_MAX_LENGTH = 50;
    private const LAST_NAME_MIN_LENGTH = 2;
    private const LAST_NAME_MAX_LENGTH = 150;
    private const EMAIL_MIN_LENGTH = 6;
    private const EMAIL_MAX_LENGTH = 255;

    private const BIO_MIN_LENGTH = 6;
    private const BIO_MAX_LENGTH = 500;

    public function firstName(string $fistName): void
    {
        $validator = v::stringType()
            ->length(self::FIRST_NAME_MIN_LENGTH, self::FIRST_NAME_MAX_LENGTH)
            ->regex("/^\p{L}+$/u")
            ->notEmpty()
            ->setName("first_name");
        $validator->assert($fistName);
    }

    public function lastName(string $lastName): void
    {
        $validator = v::stringType()
            ->length(self::LAST_NAME_MIN_LENGTH, self::LAST_NAME_MAX_LENGTH)
            ->regex("/^[\\p{L} ]+$/u")
            ->notEmpty()
            ->setName("last_name");
        $validator->assert($lastName);
    }

    public function email(string $email): void
    {
        $validator = v::email()
            ->length(self::EMAIL_MIN_LENGTH, self::EMAIL_MAX_LENGTH)
            ->notEmpty()
            ->setName("email");
        $validator->assert($email);
    }

    public function password(string $password): void
    {
        $validator = v::stringType()
            ->length(self::PASS_MIN_LENGTH, self::PASS_MAX_LENGTH)
            ->regex("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*]).+$/")
            ->notEmpty()
            ->setName("password");

        $validator->assert($password);
    }

    public function passwordConfirmation(string $password, string $passwordConfirmation): void
    {
        $validator = v::stringType()
            ->length(self::PASS_MIN_LENGTH, self::PASS_MAX_LENGTH)
            ->equals($password)
            ->notEmpty()
            ->setName("password_confirmation");

        $validator->assert($passwordConfirmation);
    }

    public function bio(string $bio): void
    {
        $validator = v::optional(
            v::stringType()
                ->length(self::BIO_MIN_LENGTH, self::BIO_MAX_LENGTH)
                ->regex('/^[\\p{L}0-9\s\,\.\-\!\?\:\;\"\'\(\)\[\]\/\&\#\%\+\s]*$/u')
        )->setName("bio");

        $validator->assert($bio);
    }
}