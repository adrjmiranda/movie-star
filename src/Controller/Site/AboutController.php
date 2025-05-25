<?php

namespace MovieStar\Controller\Site;

use MovieStar\Core\Template;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AboutController
{
    public function index(Request $request, Response $response): Response
    {
        $view = Template::render("site.pages.about");
        $response->getBody()->write($view);

        return $response;
    }
}