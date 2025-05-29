<?php

class HomeController {
    public function index(): void {
        $message = "Welcome to the PHP Starter Kit!";
        require_once __DIR__ . '/../views/home.php';
    }
}
