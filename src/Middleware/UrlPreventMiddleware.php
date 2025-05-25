<?php

namespace MovieStar\Middleware;

use MovieStar\Service\UriService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class UrlPreventMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $uriService = new UriService();
        $uriService->update();
        return $handler->handle($request);
    }
}