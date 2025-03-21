<?php
include('includes/auth.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $app_id = $_GET['id'];
    $sql = "DELETE FROM appointments WHERE app_id = $app_id";
    if ($con->query($sql)) {
        echo "<script>alert('Appointment deleted successfully!'); window.location.href='manage_appointments.php';</script>";
    } else {
        echo "<script>alert('Error deleting appointment!');</script>";
    }
}
?>