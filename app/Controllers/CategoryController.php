<?php
class CategoryController {
    public function men(): void { require __DIR__ . '/../../views/category/men.php'; }
    public function women(): void { require __DIR__ . '/../../views/category/women.php'; }
    public function unisex(): void { require __DIR__ . '/../../public/layout/unisex.html'; }
}
