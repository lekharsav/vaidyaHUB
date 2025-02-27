<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['index'])) {
    $index = $_POST['index'];

    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]); // Remove item from the cart

        // Send a success response in JSON format
        echo json_encode(["status" => "success"]);
        exit;
    }
}

// If item is not found, send an error response
echo json_encode(["status" => "error", "message" => "Item not found"]);
exit;
?>
