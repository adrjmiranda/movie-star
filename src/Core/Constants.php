<?php

namespace MovieStar\Core;

class Constants
{
    public const USER_IMAGE_PATH = "/public/assets/img/users";
    public const MOVIE_IMAGE_PATH = "/public/assets/img/movies";
    public const CATEGORY_IMAGE_PATH = "/public/assets/img/categories";
    public const NOT_SANITIZED_INPUT_LIST = [
        "description",
        "comment"
    ];
}