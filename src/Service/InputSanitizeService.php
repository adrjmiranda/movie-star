<?php

namespace MovieStar\Service;

use MovieStar\Core\Constants;

class InputSanitizeService
{
    private array $notSanitized;

    public function __construct(array $notSanitized = Constants::NOT_SANITIZED_INPUT_LIST)
    {
        $this->notSanitized = $notSanitized;
    }

    public function sanitize(mixed $input): mixed
    {
        if (is_string($input)) {
            $input = trim($input);
            $input = strip_tags($input);
            $input = htmlspecialchars($input, ENT_QUOTES, "UTF-8");

            return $input;
        }

        if (is_array($input)) {
            return array_map(fn(mixed $item): mixed => $this->sanitize($item), $input);
        }

        return $input;
    }

    public function all(array $data): array
    {
        $sanitized = [];
        foreach ($data as $field => $value) {
            if (!in_array($field, array_values($this->notSanitized))) {
                $sanitized[$field] = $this->sanitize($value);
            } else {
                $sanitized[$field] = $value;
            }
        }

        return $sanitized;
    }
}