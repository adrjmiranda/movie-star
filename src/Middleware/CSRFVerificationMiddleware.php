<?php

namespace MovieStar\Middleware;

use MovieStar\Service\AuthService;
use MovieStar\Service\FormMessageService;
use MovieStar\Service\ImageService;
use MovieStar\Service\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CSRFVerificationMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {

        $httpMethod = $_SERVER["REQUEST_METHOD"] ?? "";
        if ($httpMethod === "POST") {
            $data = $request->getParsedBody();
            $csrf = $data["csrf"] ?? "";

            $authService = new AuthService(new UserService(new ImageService()));
            if (!$authService->csrfVerify($csrf)) {
                flashError("Authorization denied. Please try again.");
                return redirectRemains();
            }
        }

        return $handler->handle($request);
    }
}