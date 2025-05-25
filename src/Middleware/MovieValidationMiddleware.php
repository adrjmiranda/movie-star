<?php

namespace MovieStar\Middleware;

use MovieStar\Service\FormMessageService;
use MovieStar\Service\InputDataService;
use MovieStar\Service\MovieValidationService;
use Respect\Validation\Exceptions\NestedValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class MovieValidationMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $data = $request->getParsedBody() ?? [];
        $movieValidationService = new MovieValidationService();

        try {
            $movieValidationService->title($data["title"] ?? "");
            $movieValidationService->description($data["description"] ?? "");
            $movieValidationService->duration($data["duration"] ?? "");
            $movieValidationService->trailer($data["trailer"] ?? "");
            $movieValidationService->categoryId($data["category_id"] ?? "");
        } catch (NestedValidationException $exception) {
            unset($data["movie"]);
            InputDataService::all($data);
            FormMessageService::all($exception->getMessages());

            return redirectRemains();
        }

        return $handler->handle($request);
    }
}