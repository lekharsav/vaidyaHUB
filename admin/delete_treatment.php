<?php
include('includes/auth.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $treatment_id = $_GET['id'];
    $sql = "DELETE FROM treatments WHERE treatment_id = $treatment_id";
    if ($con->query($sql)) {
        echo "<script>alert('Treatment deleted successfully!'); window.location.href='manage_treatments.php';</script>";
    } else {
        echo "<script>alert('Error deleting treatment!');</script>";
    }
}
?>