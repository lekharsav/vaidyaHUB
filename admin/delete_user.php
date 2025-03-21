<?php
include('includes/auth.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "DELETE FROM users WHERE u_id = $user_id";
    if ($con->query($sql)) {
        echo "<script>alert('User deleted successfully!'); window.location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user!');</script>";
    }
}
?>