<?php

namespace MovieStar\Middleware;

use MovieStar\Service\UserValidationService;
use MovieStar\Service\FormMessageService;
use MovieStar\Service\InputDataService;
use Respect\Validation\Exceptions\NestedValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoginValidationMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $data = $request->getParsedBody() ?? [];
        $userValidationService = new UserValidationService();

        try {
            $userValidationService->email($data["email"] ?? "");
        } catch (NestedValidationException $exception) {
            InputDataService::all($data);
            FormMessageService::all($exception->getMessages());

            return redirectRemains();
        }

        return $handler->handle($request);
    }
}