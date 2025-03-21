<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $doctor_id = $_GET['id'];
    $sql = "SELECT * FROM doctors WHERE d_id = $doctor_id";
    $result = $con->query($sql);
    $doctor = $result->fetch_assoc();
}

if (isset($_POST['update_doctor'])) {
    $d_id = $_POST['d_id'];
    $d_name = $_POST['d_name'];
    $specialization = $_POST['specialization'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];

    $sql = "UPDATE doctors SET d_name = '$d_name', specialization = '$specialization', email = '$email', phone = '$phone', status = $status WHERE d_id = $d_id";
    if ($con->query($sql)) {
        echo "<script>alert('Doctor updated successfully!'); window.location.href='manage_doctors.php';</script>";
    } else {
        echo "<script>alert('Error updating doctor!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Edit Doctor</h1>
    <form method="POST">
        <input type="hidden" name="d_id" value="<?php echo $doctor['d_id']; ?>">
        <div class="mb-3">
            <label for="d_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="d_name" name="d_name" value="<?php echo $doctor['d_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="specialization" class="form-label">Specialization</label>
            <input type="text" class="form-control" id="specialization" name="specialization" value="<?php echo $doctor['specialization']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $doctor['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $doctor['phone']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="1" <?php echo ($doctor['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?php echo ($doctor['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" name="update_doctor" class="btn btn-primary">Update Doctor</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>