<?php

$rootPath = rootPath();

return [
    "view_path" => "{$rootPath}/views",
    "config" => [
        "cache" => isDev() ? false : "{$rootPath}/storage/cache",
        "debug" => isDev(),
        "auto_reload" => isDev(),
        "strict_variables" => true,
        "autoescape" => "html",
        "optimizations" => -1,
    ],
    "layout_data" => [
        "site_title" => "Movie Star",
        "site_description" => "A system that allows people to input content about movies and rate content input by other users.",
        "site_keywords" => "movies, trailers, star",
        "author_name" => "Adriano Miranda",
        "canonical_url" => baseUrl(),
        "image_url" => baseUrl() . "/assets/img/logo.png",
    ]
];