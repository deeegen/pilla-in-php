<?php

require_once __DIR__ . '/app/Router.php';
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/WalkytalkyController.php';

$router = new Router();

// Home page
$router->get('/', [HomeController::class, 'index']);

// Blog view
$router->get('/blog', function () {
    require __DIR__ . '/app/views/blog.php';
});

// Walkytalky: GET for form display, POST for login/post handling
$router->get('/walkytalky', function () {
    require __DIR__ . '/app/views/walkytalky.php';
});

$router->post('/walkytalky', function () {
    require __DIR__ . '/app/views/walkytalky.php';
});

$router->resolve();
