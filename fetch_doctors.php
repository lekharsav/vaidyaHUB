<?php
include 'connect.php'; // Database connection

if (isset($_GET['specialization'])) {
    $specialization = mysqli_real_escape_string($con, $_GET['specialization']);

    $query = "SELECT d_id, d_name FROM doctors WHERE specialization = '$specialization' AND status = 1"; // Only active doctors
    $result = mysqli_query($con, $query);

    $doctors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }

    echo json_encode($doctors);
}
?>
