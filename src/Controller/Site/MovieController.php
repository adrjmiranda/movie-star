<?php

namespace MovieStar\Controller\Site;

use MovieStar\Core\Template;
use MovieStar\DAO\CategoryDAO;
use MovieStar\DAO\MovieDAO;
use MovieStar\Service\CategoryService;
use MovieStar\Service\ImageService;
use MovieStar\Service\MovieService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MovieController
{
    public function movie(Request $request, Response $response): Response
    {
        $id = (int) ($request->getAttribute("id") ?? "");
        $movieDao = new MovieDAO(
            new MovieService(new ImageService()),
            new ImageService()
        );

        $movie = $movieDao->findById($id);

        $data = [
            "movie" => $movie
        ];

        $view = Template::render("site.pages.movie", $data);
        $response->getBody()->write($view);

        return $response;
    }

    public function movies(Request $request, Response $response): Response
    {
        $movieDao = new MovieDAO(
            new MovieService(new ImageService()),
            new ImageService()
        );

        $movies = $movieDao->findByUser() ?? [];

        $data = [
            "movies" => $movies
        ];

        $view = Template::render("site.pages.movies", $data);
        $response->getBody()->write($view);

        return $response;
    }

    public function post(Request $request, Response $response): Response
    {
        $categoryDao = new CategoryDAO(
            new CategoryService(new ImageService()),
            new ImageService()
        );

        $cateorries = $categoryDao->find();

        $data = [
            "categories" => $cateorries
        ];

        $view = Template::render("site.pages.post", $data);
        $response->getBody()->write($view);

        return $response;
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        $image = $_FILES["image"] ?? null;

        if ($image["size"] !== 0) {
            $data["image"] = $image;
        }

        $movieDao = new MovieDAO(
            new MovieService(new ImageService()),
            new ImageService()
        );

        $movieId = $movieDao->create($data);

        if ($movieId > 0) {
            $moviesUri = uriFromName("movies");
            return redirect($moviesUri);
        }

        return redirectRemains();
    }

    public function remove(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];
        $movie = $data["movie"] ?? "";
        $movieDao = new MovieDAO(
            new MovieService(new ImageService()),
            new ImageService()
        );

        $movieDao->delete((int) $movie["id"]);

        return redirectBack();
    }

    public function edit(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];
        $movie = $data["movie"] ?? [];

        $categoryDao = new CategoryDAO(
            new CategoryService(new ImageService),
            new ImageService()
        );

        $categories = $categoryDao->find() ?? [];

        $view = Template::render("site.pages.edit_movie", [
            "movie" => $movie,
            "categories" => $categories
        ]);
        $response->getBody()->write($view);

        return $response;
    }

    public function update(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];
        $image = $_FILES["image"] ?? null;
        $movie = $data["movie"] ?? [];

        $movieDao = new MovieDAO(
            new MovieService(new ImageService()),
            new ImageService()
        );

        $title = $data["title"] ?? "";
        $description = $data["description"] ?? "";
        $trailer = $data["trailer"] ?? "";
        $duration = $data["duration"] ?? "";
        $categoryId = (int) ($data["category_id"] ?? "");

        $movieData = [
            "title" => $title,
            "description" => $description,
            "trailer" => $trailer,
            "duration" => $duration,
            "categoryId" => $categoryId,
        ];

        if ($image["size"] !== 0) {
            $movieData["image"] = $image;
        }

        $movieDao->update($movie["id"], $movieData);

        return redirectRemains();
    }

    public function search(Request $request, Response $response): Response
    {
        $data = $request->getQueryParams() ?? [];
        $search = $data["search"] ?? "";

        $movieDao = new MovieDAO(
            new MovieService(new ImageService()),
            new ImageService()
        );

        $results = $movieDao->findByTitle($search);
        $view = Template::render("site.pages.search", [
            "movies" => $results,
            "search" => $search
        ]);
        $response->getBody()->write($view);

        return $response;
    }
}