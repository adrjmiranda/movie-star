<?php

use MovieStar\Controller\Site\ReviewController;
use MovieStar\Middleware\MovieOwnerVerificationMiddleware;
use MovieStar\Middleware\MovieValidationMiddleware;
use MovieStar\Middleware\ReviewValidationMiddleware;
use Slim\Routing\RouteCollectorProxy;

// Controllers
use MovieStar\Controller\Site\AboutController;
use MovieStar\Controller\Site\AuthController;
use MovieStar\Controller\Site\ContactController;
use MovieStar\Controller\Site\HomeController;
use MovieStar\Controller\Site\MovieController;
use MovieStar\Controller\Site\UserController;

// Middlewares
use MovieStar\Middleware\LoginValidationMiddleware;
use MovieStar\Middleware\RegisterValidationMiddleware;
use MovieStar\Middleware\UpdateValidationMiddleware;
use MovieStar\Middleware\RequiredLoginMiddleware;
use MovieStar\Middleware\RequiredLogoutMiddleware;

// public
$app->get("/", HomeController::class . ":index")->setName("home");
$app->get("/about", AboutController::class . ":index")->setName("about");
$app->get("/contact", ContactController::class . ":index")->setName("contact");
$app->get("/movie/show/{id}", MovieController::class . ":movie")->setName("movie");
$app->get("/search", MovieController::class . ":search")->setName("movie_search");

// auth
$app->group("", function (RouteCollectorProxy $auth) {
    $auth->get("/login", AuthController::class . ":login")->setName("login_view");
    $auth->get("/register", AuthController::class . ":register")->setName("register_view");

    $auth->post("/login", AuthController::class . ":auth")
        ->add(LoginValidationMiddleware::class)
        ->setName("login");
    $auth->post("/register", AuthController::class . ":store")
        ->add(RegisterValidationMiddleware::class)
        ->setName("register");
})->add(RequiredLogoutMiddleware::class);

// user
$app->group("", function (RouteCollectorProxy $user) {
    // Users
    $user->get("/profile", UserController::class . ":index")->setName("profile");
    $user->get("/logout", AuthController::class . ":logout")->setName("logout");

    $user->post("/user/update", UserController::class . ":update")
        ->add(UpdateValidationMiddleware::class)
        ->setName("user_update");

    // Movies    
    $user->get("/post", MovieController::class . ":post")->setName("post");
    $user->get("/movies", MovieController::class . ":movies")->setName("movies");

    $user->post("/movie/create", MovieController::class . ":store")
        ->add(MovieValidationMiddleware::class)
        ->setName("movie_create");

    $user->get("/movie/delete/{id}", MovieController::class . ":remove")
        ->add(MovieOwnerVerificationMiddleware::class)
        ->setName("movie_delete");

    $user->get("/movie/edit/{id}", MovieController::class . ":edit")
        ->add(MovieOwnerVerificationMiddleware::class)
        ->setName("movie_edit");
    $user->post("/movie/update/{id}", MovieController::class . ":update")
        ->add(MovieValidationMiddleware::class)
        ->add(MovieOwnerVerificationMiddleware::class)
        ->setName("movie_update");

    // Reviews
    $user->post("/review/create", ReviewController::class . ":store")
        ->add(ReviewValidationMiddleware::class)
        ->setName("review_create");

})->add(RequiredLoginMiddleware::class);