<?php

function camelToSnake($str)
{
    return strtolower(preg_replace_callback('/([A-Z])/', function ($matches) {
        return '_' . strtolower($matches[1]);
    }, $str));
}

function convertKeysToSnakeCase($array)
{
    $newArray = [];
    foreach ($array as $key => $value) {
        $newKey = camelToSnake($key);
        $newArray[$newKey] = $value;
    }
    return $newArray;
}