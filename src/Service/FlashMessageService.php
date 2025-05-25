<?php

namespace MovieStar\Service;

use MovieStar\Core\Session;

class FlashMessageService
{
    private const FLASH_MSG_KEY = "flashmsg";
    public const FLASH_SUCCESS = "success";
    public const FLASH_WARNING = "warning";
    public const FLASH_ERROR = "error";

    public static function set(string $type, string $message): void
    {
        Session::set(self::FLASH_MSG_KEY, [
            "type" => $type,
            "message" => $message
        ]);
    }

    public static function get(): ?array
    {
        $flashMessage = Session::get(self::FLASH_MSG_KEY);
        Session::delete(self::FLASH_MSG_KEY);
        return $flashMessage;
    }
}