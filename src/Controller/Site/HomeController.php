<?php

namespace MovieStar\Controller\Site;

use MovieStar\Core\Template;
use MovieStar\DAO\MovieDAO;
use MovieStar\Service\ImageService;
use MovieStar\Service\MovieService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    public function index(Request $request, Response $response): Response
    {
        $movieDao = new MovieDAO(
            new MovieService(new ImageService()),
            new ImageService()
        );

        $movies = $movieDao->find() ?? [];

        $data = [
            "movies" => $movies
        ];

        $view = Template::render("site.pages.home", $data);
        $response->getBody()->write($view);

        return $response;
    }
}