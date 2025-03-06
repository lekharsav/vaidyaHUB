<?php
session_start();
include 'connect.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "You must be logged in to view your bookings.";
    exit();
}

$u_id = $_SESSION['uid']; // Get the logged-in user's ID

// Fetch bookings for the logged-in user
$query = "SELECT rb.*, r.room_name, r.room_type, r.price 
          FROM room_bookings rb
          INNER JOIN rooms r ON rb.room_id = r.room_id
          WHERE rb.u_id = '$u_id'
          ORDER BY rb.booking_date DESC";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Error fetching bookings: " . mysqli_error($con));
}

if (mysqli_num_rows($result) > 0) {
    while ($booking = mysqli_fetch_assoc($result)) {
        echo "<div class='booking-card'>
                <h3>{$booking['room_name']}</h3>
                <p><strong>Type:</strong> {$booking['room_type']}</p>
                <p><strong>Check-In Date:</strong> {$booking['check_in_date']}</p>
                <p><strong>Check-Out Date:</strong> {$booking['check_out_date']}</p>
                <p><strong>Status:</strong> <span class='status'>{$booking['status']}</span></p>
                <p><strong>Price:</strong> ₹{$booking['price']} per night</p>
              </div>";
    }
} else {
    echo "<p>No bookings found.</p>";
}
?>