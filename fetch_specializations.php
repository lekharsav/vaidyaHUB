<?php
include 'connect.php';

if (isset($_GET['treatment_id'])) {
    $treatment_id = $_GET['treatment_id'];

    $query = "SELECT DISTINCT specialization FROM doctors WHERE d_id IN 
              (SELECT d_id FROM doctor_treatments WHERE treatment_id = $treatment_id)";
    $result = mysqli_query($con, $query);

    $specializations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $specializations[] = $row;
    }

    echo json_encode($specializations);
}
?>
