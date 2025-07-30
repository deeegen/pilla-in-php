<?php

require_once __DIR__ . '/app/Router.php';
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/WalkytalkyController.php';

$router = new Router();

$router->get('/', [HomeController::class, 'index']);

$router->get('/blog', function () {
    require __DIR__ . '/app/views/blog.php';
});

$router->get('/walkytalky', function () {
    require __DIR__ . '/app/views/walkytalky.php';
});

$router->resolve();
