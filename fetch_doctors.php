<?php
include 'connect.php'; // Database connection

$treatment_id = $_GET['treatment_id'];

// Fetch doctors for the selected treatment
$query = "SELECT u.u_id, u.u_name 
          FROM users u
          JOIN doctor_treatments dt ON u.u_id = dt.d_id
          WHERE dt.treatment_id = ? AND u.role = 'doctor' AND u.status = 'active'";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $treatment_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$doctors = [];
while ($row = mysqli_fetch_assoc($result)) {
    $doctors[] = $row;
}

echo json_encode($doctors);
?>