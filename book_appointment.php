<?php

include 'navbar.php';
include 'connect.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$appointmentDetails = null; // Initialize appointment details

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_id = $_SESSION['uid']; // Logged-in user ID
    $d_id = $_POST['d_id']; // Doctor ID
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $specialization = $_POST['specialization'];
    $status = 'Pending'; // Default status
    $created_at = date("Y-m-d H:i:s"); // Current timestamp

    // Insert appointment into database
    $query = "INSERT INTO appointments (p_id, d_id, appointment_date, appointment_time, status, created_at, specialization) 
              VALUES ('$p_id', '$d_id', '$appointment_date', '$appointment_time', '$status', '$created_at', '$specialization')";

    if (mysqli_query($con, $query)) {
        $_SESSION['success'] = "Appointment booked successfully!";

        // Fetch the latest appointment details for the logged-in user
        $last_id = mysqli_insert_id($con);
        $fetchQuery = "SELECT a.*, d.d_name FROM appointments a 
                       JOIN doctors d ON a.d_id = d.d_id 
                       WHERE a.app_id = '$last_id' AND a.p_id = '$p_id'";
        $result = mysqli_query($con, $fetchQuery);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $appointmentDetails = mysqli_fetch_assoc($result);
        }
    } else {
        $_SESSION['error'] = "Error booking appointment: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Appointment Booking</h2>

        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']); 
                ?>
            </div>
        <?php } elseif (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); 
                ?>
            </div>
        <?php } ?>

        <?php if ($appointmentDetails) { ?>
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5>Appointment Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Doctor Name:</strong> <?php echo $appointmentDetails['d_name']; ?></p>
                    <p><strong>Specialization:</strong> <?php echo $appointmentDetails['specialization']; ?></p>
                    <p><strong>Appointment Date:</strong> <?php echo $appointmentDetails['appointment_date']; ?></p>
                    <p><strong>Appointment Time:</strong> <?php echo $appointmentDetails['appointment_time']; ?></p>
                    <p><strong>Status:</strong> <?php echo $appointmentDetails['status']; ?></p>
                </div>
            </div>
        <?php } ?>

        <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
    </div>
</body>
</html>
