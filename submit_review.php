<?php
include('connect.php');
session_start();

if (!isset($_SESSION['uid'])) {
    die("Error: You must be logged in to submit a review.");
}

$user_id = $_SESSION['uid'];
$user_name = $_SESSION['name'];
$review_text = $_POST['review_text'];
$rating = $_POST['rating'];

$sql = "INSERT INTO reviews (user_id, user_name, review_text, rating) VALUES (?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("issi", $user_id, $user_name, $review_text, $rating);

if ($stmt->execute()) {
    echo "Review submitted successfully!";
} else {
    echo "Error submitting review.";
}
?>
