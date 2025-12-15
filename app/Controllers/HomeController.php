<?php
class HomeController
{
    public function index(): void
    {
        require __DIR__ . '/../../views/home/index.php';
    }

    public function about(): void
    {
        require __DIR__ . '/../../views/other/about.php';
    }
}
