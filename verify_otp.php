<?php
session_start();
include('includes/db.php'); //

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify'])) {
    $entered_otp = $_POST['otp'];

    if ($entered_otp == $_SESSION['otp']) {
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $phone = $_SESSION['phone'];
        $hashed_password = $_SESSION['hashed_password'];

        // Use prepared statement to insert user securely
        $stmt = $con->prepare("INSERT INTO users (u_name, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

        if ($stmt->execute()) {
            // Clear session data after successful registration
            unset($_SESSION['otp'], $_SESSION['name'], $_SESSION['email'], $_SESSION['phone'], $_SESSION['hashed_password']);

            // Redirect to login page
            header('Location: login.php');
            exit();
        } else {
            die('Database error: ' . $stmt->error);
        }
    } else {
        echo "<script>alert('Invalid OTP! Please try again.'); window.location.href='register.php';</script>";
    }
} else {
    header('Location: signup.php');
    exit();
}
?>
