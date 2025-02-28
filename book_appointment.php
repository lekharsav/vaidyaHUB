<?php
session_start();
include 'connect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_id = $_SESSION['u_id']; // Logged-in user ID
    $d_id = $_POST['d_id']; // Selected doctor ID
    $treatment_id = $_POST['treatment_id']; // Selected treatment ID
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // Insert appointment into the database
    $query = "INSERT INTO appointments (p_id, d_id, treatment_id, appointment_date, appointment_time, status) 
              VALUES (?, ?, ?, ?, ?, 'pending')";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "iiiss", $p_id, $d_id, $treatment_id, $appointment_date, $appointment_time);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: appointment.php?success=1"); // Redirect with success message
    } else {
        header("Location: appointment.php?error=1"); // Redirect with error message
    }
}
?>