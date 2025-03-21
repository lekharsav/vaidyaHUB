<?php
include('includes/auth.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $sql = "DELETE FROM orders WHERE o_id = $order_id";
    if ($con->query($sql)) {
        echo "<script>alert('Order deleted successfully!'); window.location.href='manage_orders.php';</script>";
    } else {
        echo "<script>alert('Error deleting order!');</script>";
    }
}
?>