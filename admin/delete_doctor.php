<?php
include('includes/auth.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $doctor_id = $_GET['id'];
    $sql = "DELETE FROM doctors WHERE d_id = $doctor_id";
    if ($con->query($sql)) {
        echo "<script>alert('Doctor deleted successfully!'); window.location.href='manage_doctors.php';</script>";
    } else {
        echo "<script>alert('Error deleting doctor!');</script>";
    }
}
?>