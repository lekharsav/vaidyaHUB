<?php
include('includes/auth.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $medicine_id = $_GET['id'];
    $sql = "DELETE FROM medicine WHERE id = $medicine_id";
    if ($con->query($sql)) {
        echo "<script>alert('Medicine deleted successfully!'); window.location.href='manage_medicine.php';</script>";
    } else {
        echo "<script>alert('Error deleting medicine!');</script>";
    }
}
?>