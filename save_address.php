<?php
session_start();
include 'connect.php';  // Database connection

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    echo "error";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u_id = $_SESSION['uid'];  // Get logged-in user ID

    // Validate and sanitize input
    $flat_house_no = isset($_POST['flat_house_no']) ? mysqli_real_escape_string($con, trim($_POST['flat_house_no'])) : '';
    $landmark = isset($_POST['landmark']) ? mysqli_real_escape_string($con, trim($_POST['landmark'])) : '';
    $address = isset($_POST['address']) ? mysqli_real_escape_string($con, trim($_POST['address'])) : '';
    $state = isset($_POST['state']) ? mysqli_real_escape_string($con, trim($_POST['state'])) : '';
    $pin_no = isset($_POST['pin_no']) ? mysqli_real_escape_string($con, trim($_POST['pin_no'])) : '';
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($con, trim($_POST['phone'])) : '';

    // Validate required fields
    if (empty($flat_house_no) || empty($landmark) || empty($address) || empty($state) || empty($pin_no) || empty($phone)) {
        echo "error";
        exit;
    }

    // Check if the phone number is valid (optional, customize based on requirements)
    if (!preg_match('/^[0-9]{10}$/', $phone)) { 
        echo "invalid_phone";
        exit;
    }

    // Update user address
    $query = "UPDATE users 
              SET flat_house_no='$flat_house_no', 
                  landmark='$landmark', 
                  address='$address', 
                  phone='$phone', 
                  state='$state', 
                  pin_no='$pin_no' 
              WHERE u_id='$u_id'";

    if (mysqli_query($con, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
