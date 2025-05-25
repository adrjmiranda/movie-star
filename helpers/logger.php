<?php

use MovieStar\Core\Logger;

function error(string $message): void
{
    $logger = Logger::instance();
    $logger->error($message);
}

function warning(string $message): void
{
    $logger = Logger::instance();
    $logger->warning($message);
}