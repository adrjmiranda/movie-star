<?php

function allStyles(string $dir): string
{
    $styles = "";

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && strtolower($file->getFilename()) === "style.css") {
            $fullPath = $file->getRealPath();
            $content = file_get_contents($fullPath);
            $styles .= $content;
        }
    }

    return "<style>{$styles}</style>";
}