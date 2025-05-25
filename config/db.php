<?php

return [
    // [required]
    "type" => env("DB_TYPE"),
    "host" => env("DB_HOST"),
    "database" => env("DB_NAME"),
    "username" => env("DB_USER"),
    "password" => env("DB_PASS"),

    // [optional]
    "charset" => "utf8mb4",
    "collation" => "utf8mb4_general_ci",
    "port" => (int) env("DB_PORT"),

    // [optional] The table prefix. All table names will be prefixed as PREFIX_table.
    // "prefix" => "PREFIX_",

    // [optional] To enable logging. It is disabled by default for better performance.
    "logging" => isDev(),

    // [optional]
    // Error mode
    // Error handling strategies when the error has occurred.
    // PDO::ERRMODE_SILENT (default) | PDO::ERRMODE_WARNING | PDO::ERRMODE_EXCEPTION
    // Read more from https://www.php.net/manual/en/pdo.error-handling.php.
    "error" => isDev() ? PDO::ERRMODE_EXCEPTION : PDO::ERRMODE_SILENT,

    // [optional]
    // The driver_option for connection.
    // Read more from http://www.php.net/manual/en/pdo.setattribute.php.
    // "option" => [
    //     PDO::ATTR_CASE => PDO::CASE_NATURAL
    // ],

    // [optional] Medoo will execute those commands after the database is connected.
    "command" => [
        "SET SQL_MODE=ANSI_QUOTES"
    ],
];