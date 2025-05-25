<?php

function config(string $fileName): mixed
{
    $rootPath = rootPath();
    $filePath = "{$rootPath}/config/{$fileName}.php";

    if (!file_exists($filePath)) {
        throw new Exception("Configuration file {$filePath} does not exist");
    }

    return require $filePath;
}

function ftwig(): array
{
    $rootPath = rootPath();
    $functionsPath = "{$rootPath}/helpers/twig.php";
    return require $functionsPath;
}