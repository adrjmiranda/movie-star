<?php

use MovieStar\Service\InputDataService;

function inputData(array $data)
{
    foreach ($data as $field => $value) {
        InputDataService::set($field, $value);
    }
}