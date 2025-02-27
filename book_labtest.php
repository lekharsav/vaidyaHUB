<?php
include 'connect.php'; // Database conection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $test_id = mysqli_real_escape_string($con, $_POST['test_id']);
    $patient_name = mysqli_real_escape_string($con, $_POST['patient_name']);
    $test_date = mysqli_real_escape_string($con, $_POST['test_date']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $phone_no = mysqli_real_escape_string($con, $_POST['phone_no']);
    $flat_house_no = mysqli_real_escape_string($con, $_POST['flat_house_no']);
    $pin_no = mysqli_real_escape_string($con, $_POST['pin_no']);
    $u_id=isset($_SESSION)

    $query = "INSERT INTO labtest_bookings (test_id, patient_name, test_date, address, state, phone_no, flat_house_no, pin_no) 
              VALUES ('$test_id', '$patient_name', '$test_date', '$address', '$state', '$phone_no', '$flat_house_no', '$pin_no')";
    
    if (mysqli_query($con, $query)) {
        echo "<script>
                alert('Lab Test Booking Successful!');
                window.location.href = 'labtest.php';
              </script>";
    } else {
        echo "<script>
                alert('Error! Booking failed. Please try again.');
                window.history.back();
              </script>";
    }
} else {
    header("Location: labtest.php");
    exit();
}
?>
