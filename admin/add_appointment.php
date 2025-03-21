<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_POST['add_appointment'])) {
    $p_id = $_POST['p_id'];
    $d_id = $_POST['d_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = $_POST['status'];

    $sql = "INSERT INTO appointments (p_id, d_id, appointment_date, appointment_time, status) 
            VALUES ($p_id, $d_id, '$appointment_date', '$appointment_time', '$status')";
    if ($con->query($sql)) {
        echo "<script>alert('Appointment added successfully!'); window.location.href='manage_appointments.php';</script>";
    } else {
        echo "<script>alert('Error adding appointment!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Add New Appointment</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="p_id" class="form-label">Patient ID</label>
            <input type="number" class="form-control" id="p_id" name="p_id" required>
        </div>
        <div class="mb-3">
            <label for="d_id" class="form-label">Doctor ID</label>
            <input type="number" class="form-control" id="d_id" name="d_id" required>
        </div>
        <div class="mb-3">
            <label for="appointment_date" class="form-label">Appointment Date</label>
            <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
        </div>
        <div class="mb-3">
            <label for="appointment_time" class="form-label">Appointment Time</label>
            <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Pending">Pending</option>
                <option value="Confirmed">Confirmed</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
        <button type="submit" name="add_appointment" class="btn btn-primary">Add Appointment</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>