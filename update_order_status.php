<?php
include 'connect.php';

$sql = "SELECT * FROM orders WHERE status != 'Delivered'";
$result = mysqli_query($con, $sql);

$statuses = ["Ordered", "Packed", "In Transit", "Delivered"];

while ($order = mysqli_fetch_assoc($result)) {
    $o_id = $order['o_id'];
    $order_date = strtotime($order['order_date']);
    $current_time = time();
    $time_elapsed = ($current_time - $order_date) / (60 * 60 * 24); // Convert seconds to days

    // Determine new status based on time elapsed
    if ($time_elapsed >= 2) {
        $new_status = "Delivered";
    } elseif ($time_elapsed >= 1.5) {
        $new_status = "In Transit";
    } elseif ($time_elapsed >= 1) {
        $new_status = "Packed";
    } else {
        $new_status = "Ordered";
    }

    // Only update if status has changed
    if ($new_status !== $order['status']) {
        $update_query = "UPDATE orders SET status='$new_status' WHERE o_id=$o_id";
        $insert_query = "INSERT INTO order_tracking (o_id, status) VALUES ($o_id, '$new_status')";

        if (mysqli_query($con, $update_query) && mysqli_query($con, $insert_query)) {
            echo "Updated order $o_id to status: $new_status <br>";
        } else {
            echo "Error updating order $o_id: " . mysqli_error($con) . "<br>";
        }
    }
}
?>
