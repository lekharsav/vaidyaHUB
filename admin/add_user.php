<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_POST['add_user'])) {
    $u_name = $_POST['u_name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash the password
    $role = $_POST['role'];
    $status = $_POST['status'];

    $sql = "INSERT INTO users (u_name, email, password, role, status) 
            VALUES ('$u_name', '$email', '$password', '$role', $status)";
    if ($con->query($sql)) {
        echo "<script>alert('User added successfully!'); window.location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('Error adding user!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Add New User</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="u_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="u_name" name="u_name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>