<?php
session_start();
include 'connect.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $flat_house_no = mysqli_real_escape_string($con, $_POST['flat_house_no']);
    $landmark = mysqli_real_escape_string($con, $_POST['landmark']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $pin_no = mysqli_real_escape_string($con, $_POST['pin_no']);
    $u_id = $_SESSION['uid']; // Get the logged-in user's ID

    // Validate required fields
    if (empty($flat_house_no) || empty($landmark) || empty($phone) || empty($address) || empty($state) || empty($pin_no)) {
        echo "error";
        exit();
    }

    // Update the user's address in the database
    $query = "UPDATE users SET 
              flat_house_no = '$flat_house_no', 
              landmark = '$landmark', 
              phone = '$phone', 
              address = '$address', 
              state = '$state', 
              pin_no = '$pin_no' 
              WHERE u_id = $u_id";

    if (mysqli_query($con, $query)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>