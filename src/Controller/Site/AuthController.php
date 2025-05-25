<?php

namespace MovieStar\Controller\Site;

use MovieStar\Core\Template;
use MovieStar\DAO\UserDAO;
use MovieStar\Service\AuthService;
use MovieStar\Service\ImageService;
use MovieStar\Service\InputDataService;
use MovieStar\Service\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function login(Request $request, Response $response): Response
    {
        $view = Template::render("site.pages.login");
        $response->getBody()->write($view);

        return $response;
    }

    public function register(Request $request, Response $response): Response
    {
        $view = Template::render("site.pages.register");
        $response->getBody()->write($view);

        return $response;
    }

    public function auth(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        $email = $data["email"] ?? "";
        $password = $data["password"] ?? "";

        $authService = new AuthService(new UserService(new ImageService()));
        if ($authService->login($email, $password)) {
            $moviesUri = uriFromName("movies");
            return redirect($moviesUri);
        }

        InputDataService::all([
            "email" => $email,
            "password" => $password
        ]);

        return redirectRemains();
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        $authService = new AuthService(new UserService(new ImageService()));
        $userDao = new UserDAO($authService, new UserService(new ImageService()), new ImageService());

        $userId = $userDao->create($data);
        if ($authService->register($userId)) {
            $profileUri = uriFromName("profile");
            return redirect($profileUri);
        }

        InputDataService::all(data: $data);

        return redirectRemains();
    }

    public function logout(Request $request, Response $response): Response
    {
        $authService = new AuthService(new UserService(new ImageService()));
        if ($authService->logout()) {
            $loginUri = uriFromName("login_view");
            return redirect($loginUri);
        }

        return redirectRemains();
    }
}