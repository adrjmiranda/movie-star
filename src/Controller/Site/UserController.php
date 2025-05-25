<?php

namespace MovieStar\Controller\Site;

use MovieStar\Core\Session;
use MovieStar\Core\Template;
use MovieStar\DAO\UserDAO;
use MovieStar\Service\AuthService;
use MovieStar\Service\ImageService;
use MovieStar\Service\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    public function index(Request $request, Response $response): Response
    {
        $view = Template::render("site.pages.profile");
        $response->getBody()->write($view);

        return $response;
    }


    public function update(Request $request, Response $response): Response
    {
        $id = (int) (Session::get("user")["id"] ?? "");
        $data = $request->getParsedBody() ?? [];
        $image = $_FILES["image"] ?? null;

        $email = $data["email"] ?? "";
        $firstName = $data["first_name"] ?? "";
        $lastName = $data["last_name"] ?? "";
        $bio = $data["bio"] ?? "";
        $password = $data["password"] ?? "";

        $userData = [
            "firstName" => $firstName,
            "lastName" => $lastName,
            "email" => $email,
            "bio" => $bio,
            "password" => $password
        ];

        if ($image["size"] !== 0) {
            $userData["image"] = $image;
        }

        $userDao = new UserDAO(
            new AuthService(new UserService(new ImageService)),
            new UserService(new ImageService),
            new ImageService()
        );

        $userDao->update($id, $userData);

        return redirectRemains();
    }
}