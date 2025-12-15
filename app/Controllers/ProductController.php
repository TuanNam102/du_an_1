<?php
class ProductController {
    public function details(): void {
        if (!isset($_GET['slug'])) {
            header('Location: main.php?r=home');
            return;
        }
        require __DIR__ . '/../../views/product/details.php';
    }
}
