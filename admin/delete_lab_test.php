<?php
include('includes/auth.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $test_id = $_GET['id'];
    $sql = "DELETE FROM lab_tests WHERE lt_id = $test_id";
    if ($con->query($sql)) {
        echo "<script>alert('Lab test deleted successfully!'); window.location.href='manage_lab_tests.php';</script>";
    } else {
        echo "<script>alert('Error deleting lab test!');</script>";
    }
}
?>