<?php
session_start();
include 'connect.php';  // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u_id = $_SESSION['uid'];  // Get logged-in user ID
    $flat_house_no = mysqli_real_escape_string($con, $_POST['flat_house_no']);
    $landmark = mysqli_real_escape_string($con, $_POST['landmark']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $pin_no = mysqli_real_escape_string($con, $_POST['pin_no']);

    // Update user address
    $query = "UPDATE users SET flat_house_no='$flat_house_no', landmark='$landmark', address='$address', state='$state', pin_no='$pin_no' WHERE u_id='$u_id'";

    if (mysqli_query($con, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
