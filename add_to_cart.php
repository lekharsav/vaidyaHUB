<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get product details from the request
$id = isset($_POST['id']) ? $_POST['id'] : null;
$name = isset($_POST['name']) ? $_POST['name'] : null;
$price = isset($_POST['price']) ? $_POST['price'] : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if (!$id || !$name || !$price) {
    echo "Invalid data!";
    exit;
}

// Check if product is already in the cart
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] += $quantity; // Increase quantity
} else {
    $_SESSION['cart'][$id] = [
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity
    ];
}

echo "success";
