<?php

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

try{
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    $router = new Framework\Routing\Router();
    $routes = require_once __DIR__ . '/../app/routes.php';
    $routes($router);
    print $router->dispatch();
}
catch (\PDOException $e) {
    if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev') {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
        throw $e;
    }
    var_dump('An error has occurred');
}
