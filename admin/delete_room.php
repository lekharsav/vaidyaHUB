<?php
include('includes/auth.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];
    $sql = "DELETE FROM rooms WHERE room_id = $room_id";
    if ($con->query($sql)) {
        echo "<script>alert('Room deleted successfully!'); window.location.href='manage_rooms.php';</script>";
    } else {
        echo "<script>alert('Error deleting room!');</script>";
    }
}
?>