<?php

function ddump(mixed ...$values): void
{
    foreach ($values as $value) {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
        echo "<br>";
        die();
    }
}