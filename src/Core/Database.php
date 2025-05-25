<?php

namespace MovieStar\Core;

use Medoo\Medoo;

class Database
{
    private static ?Medoo $db = null;

    private function __construct()
    {
    }

    public static function instance(): Medoo
    {
        if (self::$db === null) {
            self::$db = new Medoo(config("db"));
        }

        return self::$db;
    }
}