<?php

namespace MovieStar\Middleware;

use MovieStar\Core\Session;
use MovieStar\DAO\MovieDAO;
use MovieStar\Service\ImageService;
use MovieStar\Service\MovieService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;


class MovieOwnerVerificationMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $id = (int) ($route->getArgument("id") ?? "");
        $userId = (int) (Session::get("user")["id"] ?? "");
        $data = $request->getParsedBody() ?? [];

        $movieDao = new MovieDAO(
            new MovieService(new ImageService()),
            new ImageService()
        );

        $movie = $movieDao->findById($id);

        if (!$movie) {
            flashWarning("Movie not found");
            return redirectBack();
        }

        if ($userId !== (int) $movie["userId"]) {
            flashError("You do not have permission to edit this movie post.");
            return redirectBack();
        }

        $data["movie"] = $movie;
        return $handler->handle($request->withParsedBody($data));
    }
}