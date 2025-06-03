<?php

class BlogController {
    public function index(): void {
        $title = 'Blog';
        ob_start();
        include __DIR__ . '/../views/blog.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function embed(): void {
        include __DIR__ . '/../views/blog.php';
    }
}
