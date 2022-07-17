<?php

use Framework\Routing\Router;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;

return function (Router $router) {
    $router->add(
        'GET',
        '/api/currencies',
        [new CurrencyController($router), 'index'],
    );

    $router->add(
        'GET',
        '/currencies/{id}',
        [new CurrencyController($router), 'find'],
    );

    $router->add(
        'POST',
        '/api/currencies',
        [new CurrencyController($router), 'save'],
    );

    $router->add(
        'GET',
        '/api/countries',
        [new CountryController($router), 'index'],
    );

    $router->add(
        'GET',
        '/countries/{id}',
        [new CountryController($router), 'find'],
    );

    $router->add(
        'POST',
        '/api/countries',
        [new CountryController($router), 'save'],
    );

   


    $router->errorHandler(404, fn () => 'whoops!, Page not Found');
};
