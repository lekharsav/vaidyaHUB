<?php
session_start(); // Start the session
include "connect.php";

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
  echo "error";
  exit();
}

// Get form data
$doctorId = $_POST['doctor_id'];
$userId = $_POST['user_id'];
$reviewText = $_POST['review_text'];

// Insert the review into the database
$insertQuery = "INSERT INTO doctor_reviews (doctor_id, user_id, review_text) 
                VALUES ($doctorId, $userId, '$reviewText')";
if (mysqli_query($con, $insertQuery)) {
  echo "success";
} else {
  echo "error";
}
?>