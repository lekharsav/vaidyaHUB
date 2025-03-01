<?php
session_start();
include 'connect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $test_id = mysqli_real_escape_string($con, $_POST['test_id']);
    $patient_name = mysqli_real_escape_string($con, $_POST['patient_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone_no = mysqli_real_escape_string($con, $_POST['phone_no']);
    $test_date = mysqli_real_escape_string($con, $_POST['test_date']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $flat_house_no = mysqli_real_escape_string($con, $_POST['flat_house_no']);
    $pin_no = mysqli_real_escape_string($con, $_POST['pin_no']);
    
    // Get logged-in user ID from session
    $u_id = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;

    // Check if user is logged in
    if (!$u_id) {
        echo "<script>
                alert('You must be logged in to book a test.');
                window.location.href = 'login.php';
              </script>";
        exit();
    }

    // Insert query
    $query = "INSERT INTO labtest_bookings (test_id, patient_name, email, phone_no, test_date, booking_date, status, address, state, flat_house_no, pin_no, u_id) 
              VALUES ('$test_id', '$patient_name', '$email', '$phone_no', '$test_date', NOW(), 'Pending', '$address', '$state', '$flat_house_no', '$pin_no', '$u_id')";
    
    if (mysqli_query($con, $query)) {
        echo "<script>
                alert('Lab Test Booking Successful!');
                window.location.href = 'labtest.php';
              </script>";
    } else {
        echo "<script>
                alert('Error! Booking failed. Please try again.');
                window.history.back();
              </script>";
    }
} else {
    header("Location: labtest.php");
    exit();
}
?>
