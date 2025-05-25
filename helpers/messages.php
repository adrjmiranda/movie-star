<?php

use MovieStar\Service\FlashMessageService;
use MovieStar\Service\FormMessageService;

function flashSuccess(string $message): void
{
    FlashMessageService::set(FlashMessageService::FLASH_SUCCESS, $message);
}

function flashWarning(string $message): void
{
    FlashMessageService::set(FlashMessageService::FLASH_WARNING, $message);
}

function flashError(string $message): void
{
    FlashMessageService::set(FlashMessageService::FLASH_ERROR, $message);
}

function formMessage(array $messages): void
{
    foreach ($messages as $field => $message) {
        FormMessageService::set($field, $message);
    }
}