<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $app_id = $_GET['id'];
    $sql = "SELECT * FROM appointments WHERE app_id = $app_id";
    $result = $con->query($sql);
    $appointment = $result->fetch_assoc();
}

if (isset($_POST['update_appointment'])) {
    $app_id = $_POST['app_id'];
    $p_id = $_POST['p_id'];
    $d_id = $_POST['d_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = $_POST['status'];

    $sql = "UPDATE appointments SET p_id = $p_id, d_id = $d_id, appointment_date = '$appointment_date', appointment_time = '$appointment_time', status = '$status' WHERE app_id = $app_id";
    if ($con->query($sql)) {
        echo "<script>alert('Appointment updated successfully!'); window.location.href='manage_appointments.php';</script>";
    } else {
        echo "<script>alert('Error updating appointment!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Edit Appointment</h1>
    <form method="POST">
        <input type="hidden" name="app_id" value="<?php echo $appointment['app_id']; ?>">
        <div class="mb-3">
            <label for="p_id" class="form-label">Patient ID</label>
            <input type="number" class="form-control" id="p_id" name="p_id" value="<?php echo $appointment['p_id']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="d_id" class="form-label">Doctor ID</label>
            <input type="number" class="form-control" id="d_id" name="d_id" value="<?php echo $appointment['d_id']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="appointment_date" class="form-label">Appointment Date</label>
            <input type="date" class="form-control" id="appointment_date" name="appointment_date" value="<?php echo $appointment['appointment_date']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="appointment_time" class="form-label">Appointment Time</label>
            <input type="time" class="form-control" id="appointment_time" name="appointment_time" value="<?php echo $appointment['appointment_time']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Pending" <?php echo ($appointment['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Confirmed" <?php echo ($appointment['status'] == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                <option value="Completed" <?php echo ($appointment['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                <option value="Cancelled" <?php echo ($appointment['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" name="update_appointment" class="btn btn-primary">Update Appointment</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>