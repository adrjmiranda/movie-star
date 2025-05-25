<?php

namespace MovieStar\Service;

use MovieStar\Core\Session;

class FormMessageService
{
    private const FORM_MSG_KEY = "formmsg";

    public static function set(string $field, string $message): void
    {
        $formMessages = Session::get(self::FORM_MSG_KEY) ?? [];
        $formMessages[$field] = str_replace("_", " ", $message);
        Session::set(self::FORM_MSG_KEY, $formMessages);
    }

    public static function all(array $data): void
    {
        foreach ($data as $field => $value) {
            self::set($field, $value);
        }
    }

    public static function get(string $field): ?string
    {
        $formMessages = Session::get(self::FORM_MSG_KEY) ?? [];
        $message = $formMessages[$field] ?? null;
        unset($formMessages[$field]);
        Session::set(self::FORM_MSG_KEY, $formMessages);
        return $message;
    }
}