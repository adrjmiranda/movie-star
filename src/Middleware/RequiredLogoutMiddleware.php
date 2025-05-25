<?php

namespace MovieStar\Middleware;

use MovieStar\Service\AuthService;
use MovieStar\Service\ImageService;
use MovieStar\Service\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class RequiredLogoutMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $authService = new AuthService(new UserService(new ImageService()));
        if ($authService->isAuth()) {
            return redirectBack();
        }

        return $handler->handle($request);
    }
}