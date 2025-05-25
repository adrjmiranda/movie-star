<?php

namespace MovieStar\Service;

use Respect\Validation\Validator as v;

class MovieValidationService
{
    private const TITLE_MIN_LENGTH = 2;
    private const TITLE_MAX_LENGTH = 255;
    private const DESCRIPTION_MIN_LENGTH = 10;
    private const DESCRIPTION_MAX_LENGTH = 5000;
    private const TRAILER_MAX_LENGTH = 500;

    public function title(string $title): void
    {
        $validator = v::stringType()
            ->length(self::TITLE_MIN_LENGTH, self::TITLE_MAX_LENGTH)
            ->regex('/^[\\p{L}0-9\s\-:,.&\'"]+$/u')
            ->notEmpty()
            ->setName("title");

        $validator->assert($title);
    }

    public function description(string $description): void
    {
        $validator = v::stringType()
            ->length(self::DESCRIPTION_MIN_LENGTH, self::DESCRIPTION_MAX_LENGTH)
            ->regex('/^[\p{L}0-9\s,.!?:;"\'()$$\/&@-]*$/u')
            ->notEmpty()
            ->setName("description");

        $validator->assert($description);
    }

    public function trailer(string $trailer): void
    {
        $validator = v::optional(
            v::url()
                ->length(null, self::TRAILER_MAX_LENGTH)
                ->notEmpty()
        )->setName("trailler");

        $validator->assert($trailer);
    }

    public function duration(string $duration): void
    {
        $validator = v::optional(
            v::intVal()
                ->min(1)
        )->setName("duration");

        $validator->assert($duration);
    }

    public function categoryId(string $categoryId): void
    {
        $validator = v::optional(
            v::intVal()
                ->min(1)
        )->setName("category_id");

        $validator->assert($categoryId);
    }
}