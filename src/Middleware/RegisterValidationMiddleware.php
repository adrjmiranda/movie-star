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

class RegisterValidationMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $data = $request->getParsedBody() ?? [];
        $userValidationService = new UserValidationService();

        try {
            $userValidationService->firstName($data["first_name"] ?? "");
            $userValidationService->lastName($data["last_name"] ?? "");
            $userValidationService->email($data["email"] ?? "");
            $userValidationService->password($data["password"] ?? "");
            $userValidationService->passwordConfirmation($data["password"] ?? "", $data["password_confirmation"] ?? "");
        } catch (NestedValidationException $exception) {
            InputDataService::all($data);
            FormMessageService::all($exception->getMessages());

            return redirectRemains();
        }

        return $handler->handle($request);
    }
}