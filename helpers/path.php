<?php

use MovieStar\Core\Template;
use MovieStar\Service\UriService;
use Slim\Psr7\Response;

function rootPath(): string
{
    return dirname(dirname(__FILE__));
}

function fullPath(string $filePath): string
{
    $path = rootPath() . DIRECTORY_SEPARATOR . $filePath;
    return str_replace("//", "/", $path);
}

function baseUrl(): string
{
    $protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") ? 'https' : 'http';
    $host = $_SERVER["HTTP_HOST"];
    $scriptPath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");

    return "{$protocol}://{$host}{$scriptPath}";
}

function redirectBack(): Response
{
    $response = new Response();
    $uriSerice = new UriService();

    $previousUri = $uriSerice->previous();

    return $response->withStatus(303)->withHeader("Location", "{$previousUri}");
}

function redirectRemains(): Response
{
    $response = new Response();
    $uriSerice = new UriService();

    $currentUri = $uriSerice->current();

    return $response->withStatus(303)->withHeader("Location", "{$currentUri}");
}

function uriFromName(string $name, array $data = [], array $queryParams = [])
{
    $app = Template::getApp();
    $routeParser = $app->getRouteCollector()->getRouteParser();
    return $routeParser->urlFor($name, $data, $queryParams);
}

function redirect(string $to): Response
{
    $response = new Response();
    return $response->withStatus(303)->withHeader("Location", "{$to}");
}
