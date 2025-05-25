<?php

namespace MovieStar\Core;

use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Logger
{
    private static ?Monolog $instance = null;

    public static function instance(): Monolog
    {
        if (self::$instance === null) {
            $log = new Monolog("MovieStar");

            $rootPath = rootPath();
            $logsPath = "{$rootPath}/logs";
            $logFile = "{$logsPath}/app.log";

            if (!is_dir($logsPath)) {
                mkdir($logsPath, 0755, true);
            }

            $formatter = new LineFormatter("[%datetime%] %level_name%: %message% %context%\n", null, true, true);
            $handler = new StreamHandler($logFile, Monolog::DEBUG);
            $handler->setFormatter($formatter);

            $log->pushHandler($handler);

            self::$instance = $log;
        }

        return self::$instance;
    }
}