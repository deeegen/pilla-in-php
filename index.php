<?php

require_once __DIR__ . '/app/Router.php';
require_once __DIR__ . '/app/controllers/HomeController.php';

$router = new Router();

$router->get('/', [HomeController::class, 'index']);

$router->get('/blog', function () {
    require __DIR__ . '/app/views/blog.php';
});

$router->resolve();
