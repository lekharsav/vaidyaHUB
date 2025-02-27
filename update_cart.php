<?php
session_start();

if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $id = $_POST['id'];
    $quantity = (int)$_POST['quantity'];

    if (isset($_SESSION['cart'][$id]) && $quantity > 0) {
        $_SESSION['cart'][$id]['quantity'] = $quantity;
        echo "success";
    } else {
        echo "error";
    }
}
?>
