<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_POST['update_status'])) {
    $app_id = $_POST['app_id'];
    $status = $_POST['status'];
    $sql = "UPDATE appointments SET status = '$status' WHERE app_id = $app_id";
    if ($con->query($sql)) {
        echo "<script>alert('Appointment status updated successfully!'); window.location.href='manage_appointments.php';</script>";
    } else {
        echo "<script>alert('Error updating appointment status!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Manage Appointments</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT a.*, u.u_name AS patient_name, d.d_name AS doctor_name 
                        FROM appointments a
                        JOIN users u ON a.p_id = u.u_id
                        JOIN doctors d ON a.d_id = d.d_id";
                $result = $con->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['patient_name']}</td>
                            <td>{$row['doctor_name']}</td>
                            <td>{$row['appointment_date']}</td>
                            <td>{$row['appointment_time']}</td>
                            <td>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='app_id' value='{$row['app_id']}'>
                                    <select name='status' onchange='this.form.submit()'>
                                        <option value='Pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                        <option value='Confirmed' " . ($row['status'] == 'Confirmed' ? 'selected' : '') . ">Confirmed</option>
                                        <option value='Completed' " . ($row['status'] == 'Completed' ? 'selected' : '') . ">Completed</option>
                                        <option value='Cancelled' " . ($row['status'] == 'Cancelled' ? 'selected' : '') . ">Cancelled</option>
                                    </select>
                                    <input type='hidden' name='update_status'>
                                </form>
                            </td>
                            <td>
                                <a href='edit_appointment.php?id={$row['app_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_appointment.php?id={$row['app_id']}' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>