<?php
session_start();
include 'connect.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $room_id = mysqli_real_escape_string($con, $_POST['room_id']);
    $check_in_date = mysqli_real_escape_string($con, $_POST['check_in_date']);
    $check_out_date = mysqli_real_escape_string($con, $_POST['check_out_date']);
    $patient_name = mysqli_real_escape_string($con, $_POST['patient_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone_no = mysqli_real_escape_string($con, $_POST['phone_no']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $flat_house_no = mysqli_real_escape_string($con, $_POST['flat_house_no']);
    $pin_no = mysqli_real_escape_string($con, $_POST['pin_no']);
    $u_id = $_SESSION['uid']; // Get the logged-in user's ID

    // Insert booking into the database
    $query = "INSERT INTO room_bookings (room_id, u_id, check_in_date, check_out_date, patient_name, email, phone_no, address, state, flat_house_no, pin_no, booking_date, status) 
              VALUES ('$room_id', '$u_id', '$check_in_date', '$check_out_date', '$patient_name', '$email', '$phone_no', '$address', '$state', '$flat_house_no', '$pin_no', NOW(), 'Pending')";

    if (mysqli_query($con, $query)) {
        // Update room availability
        mysqli_query($con, "UPDATE rooms SET availability = 'Booked' WHERE room_id = '$room_id'");
        echo "<script>
                alert('Booking successful!');
                window.location.href = 'user_bookings.php';
              </script>";
    } else {
        echo "<script>
                alert('Error! Booking failed. Please try again.');
                window.history.back();
              </script>";
    }
} else {
    // If the form is not submitted, redirect to the room booking page
    header("Location: roombooking.php");
    exit();
}

// Close the database connection
mysqli_close($con);
?>