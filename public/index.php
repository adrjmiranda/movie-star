<?php

require_once __DIR__ . "/../bootstrap.php";

use MovieStar\Core\Template;
use MovieStar\Middleware\CSRFVerificationMiddleware;
use MovieStar\Middleware\SanitizedInputMiddleware;
use MovieStar\Middleware\SessionMiddleware;
use MovieStar\Middleware\UrlPreventMiddleware;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

if (isDev()) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$app->add(CSRFVerificationMiddleware::class);
$app->add(SanitizedInputMiddleware::class);
$app->add(UrlPreventMiddleware::class);
$app->add(SessionMiddleware::class);

// Add routes
require_once rootPath() . "/routes/admin.php";
require_once rootPath() . "/routes/site.php";

Template::setApp($app);

$app->run();
