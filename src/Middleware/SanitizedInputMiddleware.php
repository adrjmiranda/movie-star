<?php

namespace MovieStar\Middleware;

use MovieStar\Service\InputSanitizeService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SanitizedInputMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $inputSanitizeService = new InputSanitizeService();

        $data = $request->getParsedBody() ?? [];
        $sanitizedData = $inputSanitizeService->all($data);

        return $handler->handle($request->withParsedBody($sanitizedData));
    }
}