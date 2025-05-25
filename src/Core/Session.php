<?php

namespace MovieStar\Core;

class Session
{
    private const COOKIE_LIFETIME = 3600;

    public static function init(array $options = [])
    {
        ini_set("session.cookie_secure", isDev() ? "0" : "1");
        ini_set("session.cookie_httponly", "1");
        ini_set("session.use_only_cookies", "1");

        $cookieParams = session_get_cookie_params();
        session_set_cookie_params([
            "lifetime" => (int) env("COOKIE_LIFETIME", self::COOKIE_LIFETIME),
            "path" => $cookieParams["path"],
            "domain" => $cookieParams["domain"],
            "secure" => !isDev(),
            "httponly" => true
        ]);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["initialized"])) {
            $_SESSION["initialized"] = true;
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        if (!is_string($key) && !is_int($key)) {
            throw new \InvalidArgumentException("Invalid session key.");
        }
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public static function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function clear(): void
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
    }
}