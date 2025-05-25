<?php

namespace MovieStar\Core;

use Psr\Container\ContainerInterface;
use Slim\App;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class Template
{
    private static ?FilesystemLoader $loader = null;
    private static ?Environment $environment = null;

    private static App $app;

    public static function setApp(App $app): void
    {
        self::$app = $app;
    }

    public static function getApp(): App
    {
        return self::$app;
    }


    private static function getOptions(): array
    {
        return config("template");
    }

    private static function getLoader(): FilesystemLoader
    {
        if (self::$loader === null) {
            $options = self::getOptions();
            self::$loader = new FilesystemLoader($options["view_path"]);
        }

        return self::$loader;
    }

    private static function addsFunctions(Environment $environment): void
    {
        $functions = ftwig();
        foreach ($functions as $name => $instance) {
            $environment->addFunction(new TwigFunction($name, $instance));
        }
    }

    private static function getEnvironment(): Environment
    {
        if (self::$environment === null) {
            $loader = self::getLoader();
            $options = self::getOptions();
            self::$environment = new Environment($loader, $options["config"]);
            self::addsFunctions(self::$environment);
        }

        return self::$environment;
    }

    public static function render(string $view, array $data = []): string
    {
        $options = self::getOptions();
        $data = array_merge($data, $options["layout_data"]);

        $environment = self::getEnvironment();
        $viewName = str_replace(".", "/", $view) . "/index.html";
        $template = $environment->load($viewName);

        return $template->render($data);
    }
}