<?php

namespace MovieStar\Service;

use Respect\Validation\Validator as v;

class ReviewValidationService
{
    private const MIN_RATING = 1;
    private const MAX_RATING = 10;
    private const COMMENT_MIN_LENGTH = 10;
    private const COMMENT_MAX_LENGTH = 5000;

    public function comment(string $comment): void
    {
        $validator = v::stringType()
            ->length(self::COMMENT_MIN_LENGTH, self::COMMENT_MAX_LENGTH)
            ->regex('/^[\\p{L}0-9\s\,\.\-\!\?\:\;\"\'\(\)\[\]\/\&\@\#\<\>\+\=]*$/u')
            ->notEmpty()
            ->setName("comment");

        $validator->assert($comment);
    }

    public function rating(string $rating): void
    {
        $validator = v::intVal()
            ->between(self::MIN_RATING, self::MAX_RATING)
            ->notEmpty()
            ->setName("rating");

        $validator->assert($rating);
    }

    public function movieId(string $movieId): void
    {
        $validator = v::optional(
            v::intVal()
                ->min(1)
        )->setName("movie_id");

        $validator->assert($movieId);
    }
}