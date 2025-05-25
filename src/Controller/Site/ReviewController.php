<?php

namespace MovieStar\Controller\Site;

use MovieStar\DAO\ReviewDAO;
use MovieStar\Service\ImageService;
use MovieStar\Service\ReviewService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReviewController
{
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];
        $reviewDao = new ReviewDAO(
            new ReviewService(),
            new ImageService(),
        );
        $reviewDao->create($data);

        return redirectRemains();
    }
}