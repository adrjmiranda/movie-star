<?php

use MovieStar\Core\Session;
use MovieStar\Core\Template;
use MovieStar\Service\AuthService;
use MovieStar\Service\FlashMessageService;
use MovieStar\Service\FormMessageService;
use MovieStar\Service\ImageService;
use MovieStar\Service\InputDataService;
use MovieStar\Service\ReviewService;
use MovieStar\Service\UserService;

$rootPath = rootPath();
$authService = new AuthService(new UserService(new ImageService()));
$reviewService = new ReviewService();

$baseUrl = fn(): string => baseUrl();
$otherStyles = fn(string $subdir): string => allStyles("{$rootPath}/views/{$subdir}");
$urlFor = function (string $name, array $data = [], array $queryParams = []) {
    $app = Template::getApp();
    $routeParser = $app->getRouteCollector()->getRouteParser();
    return $routeParser->urlFor($name, $data, $queryParams);
};
$isAuth = fn(): bool => $authService->isAuth();
$flashMessage = fn(): ?array => FlashMessageService::get();
$formMessage = fn(string $field): ?string => FormMessageService::get($field);
$inputData = fn(string $field): ?string => InputDataService::get($field);
$csrf = fn(): string => $authService->csrf();
$session = fn(string $key): mixed => Session::get($key);
$userHasAlreadyLeftAReview = fn(int $movieId): bool => $reviewService->userHasAlreadyLeftAReview($movieId);

return [
    "base_url" => $baseUrl,
    "other_styles" => $otherStyles,
    "url_for" => $urlFor,
    "is_auth" => $isAuth,
    "flash_message" => $flashMessage,
    "form_message" => $formMessage,
    "input_data" => $inputData,
    "csrf" => $csrf,
    "session" => $session,
    "user_has_already_left_a_review" => $userHasAlreadyLeftAReview
];