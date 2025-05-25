<?php

namespace MovieStar\Middleware;

use MovieStar\Service\FormMessageService;
use MovieStar\Service\InputDataService;
use MovieStar\Service\ReviewValidationService;
use Respect\Validation\Exceptions\NestedValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ReviewValidationMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $data = $request->getParsedBody() ?? [];
        $reviewValidationService = new ReviewValidationService();

        try {
            $reviewValidationService->comment($data["comment"] ?? "");
            $reviewValidationService->rating($data["rating"] ?? "");
            $reviewValidationService->movieId($data["movie_id"] ?? "");
        } catch (NestedValidationException $exception) {
            InputDataService::all($data);
            FormMessageService::all($exception->getMessages());

            return redirectRemains();
        }

        return $handler->handle($request);
    }
}