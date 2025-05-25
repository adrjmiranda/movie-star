<?php

function env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}

function isDev(): bool
{
    return env("APP_MODE") === "development";
}