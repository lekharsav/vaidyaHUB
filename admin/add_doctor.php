<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_POST['add_doctor'])) {
    $d_name = $_POST['d_name'];
    $specialization = $_POST['specialization'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];

    $sql = "INSERT INTO doctors (d_name, specialization, email, phone, status) 
            VALUES ('$d_name', '$specialization', '$email', '$phone', $status)";
    if ($con->query($sql)) {
        echo "<script>alert('Doctor added successfully!'); window.location.href='manage_doctors.php';</script>";
    } else {
        echo "<script>alert('Error adding doctor!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Add New Doctor</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="d_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="d_name" name="d_name" required>
        </div>
        <div class="mb-3">
            <label for="specialization" class="form-label">Specialization</label>
            <input type="text" class="form-control" id="specialization" name="specialization" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <button type="submit" name="add_doctor" class="btn btn-primary">Add Doctor</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>