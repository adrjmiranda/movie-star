<?php

namespace MovieStar\Service;

use MovieStar\Core\Session;

class InputDataService
{
    private const INPUT_DATA_KEY = "inputdata";

    public static function set(string $field, string $value): void
    {
        $inputValues = Session::get(self::INPUT_DATA_KEY) ?? [];
        $inputValues[$field] = $value;
        Session::set(self::INPUT_DATA_KEY, $inputValues);
    }

    public static function all(array $data): void
    {
        foreach ($data as $field => $value) {
            self::set($field, $value);
        }
    }

    public static function get(string $field): ?string
    {
        $inputValues = Session::get(self::INPUT_DATA_KEY, []);
        $value = $inputValues[$field] ?? null;
        unset($inputValues[$field]);
        Session::set(self::INPUT_DATA_KEY, $inputValues);

        return $value;
    }
}