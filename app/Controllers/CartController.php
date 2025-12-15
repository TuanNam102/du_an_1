<?php
class CartController {
    public function index(): void { require __DIR__ . '/../../views/cart/index.php'; }
    public function update(): void { require __DIR__ . '/../../views/cart/update.php'; }
    public function add(): void { require __DIR__ . '/../../views/cart/add.php'; }
    public function delete(): void { require __DIR__ . '/../../views/cart/delete.php'; }
}
