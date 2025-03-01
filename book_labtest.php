<?php
session_start(); // Start the session
include 'connect.php'; // Database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $test_id = trim(mysqli_real_escape_string($con, $_POST['test_id']));
    $patient_name = trim(mysqli_real_escape_string($con, $_POST['patient_name']));
    $email = trim(mysqli_real_escape_string($con, $_POST['email']));
    $phone_no = trim(mysqli_real_escape_string($con, $_POST['phone_no']));
    $test_date = trim(mysqli_real_escape_string($con, $_POST['test_date']));
    $address = trim(mysqli_real_escape_string($con, $_POST['address']));
    $state = trim(mysqli_real_escape_string($con, $_POST['state']));
    $flat_house_no = trim(mysqli_real_escape_string($con, $_POST['flat_house_no']));
    $pin_no = trim(mysqli_real_escape_string($con, $_POST['pin_no']));
    $u_id = isset($_SESSION['u_id']) ? trim(mysqli_real_escape_string($con, $_SESSION['u_id'])) : null;

    // Debug: Print all variables (optional for testing)
    /*
    echo "<pre>";
    var_dump($test_id, $patient_name, $email, $phone_no, $test_date, $address, $state, $flat_house_no, $pin_no, $u_id);
    echo "</pre>";
    */

    // Check if all required fields are filled
    if (empty($test_id) || empty($patient_name) || empty($email) || empty($phone_no) || empty($test_date) || empty($address) || empty($state) || empty($flat_house_no) || empty($pin_no) || empty($u_id)) {
        echo "<script>
                alert('All fields are required!');
                window.history.back();
              </script>";
        exit();
    }

    // Prepare the SQL query using prepared statements
    $query = "INSERT INTO labtest_bookings (test_id, patient_name, email, phone_no, test_date, address, state, flat_house_no, pin_no, u_id) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($con, $query);
    if ($stmt) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, 'ssssssssss', $test_id, $patient_name, $email, $phone_no, $test_date, $address, $state, $flat_house_no, $pin_no, $u_id);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
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
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>
                alert('Error! Booking failed. Please try again.');
                window.history.back();
              </script>";
    }
} else {
    // If the form is not submitted, redirect to the lab test page
    header("Location: labtest.php");
    exit();
}

// Close the database connection
mysqli_close($con);
?>