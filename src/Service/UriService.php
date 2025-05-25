<?php

namespace MovieStar\Service;

use MovieStar\Core\Session;

class UriService
{
    private const PREVIOUS_URI_KEY = "previous_uri";
    private const CURRENT_URI_KEY = "current_uri";

    public function previous(): string
    {
        return Session::get(self::PREVIOUS_URI_KEY, Session::get(self::CURRENT_URI_KEY, "/"));
    }

    public function current(): string
    {
        return Session::get(self::CURRENT_URI_KEY, "/");
    }

    public function update(): void
    {
        $uri = $_SERVER["REQUEST_URI"] ?? "/";
        $method = $_SERVER["REQUEST_METHOD"] ?? "";

        $currentUri = Session::get(self::CURRENT_URI_KEY, $uri);
        if ($method === "GET") {
            Session::set(self::PREVIOUS_URI_KEY, $currentUri);
            Session::set(self::CURRENT_URI_KEY, $uri);
        }
    }
}