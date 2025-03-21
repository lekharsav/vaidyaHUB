<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE u_id = $user_id";
    $result = $con->query($sql);
    $user = $result->fetch_assoc();
}

if (isset($_POST['update_user'])) {
    $user_id = $_POST['u_id'];
    $u_name = $_POST['u_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    $sql = "UPDATE users SET u_name = '$u_name', email = '$email', role = '$role', status = $status WHERE u_id = $user_id";
    if ($con->query($sql)) {
        echo "<script>alert('User updated successfully!'); window.location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('Error updating user!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Edit User</h1>
    <form method="POST">
        <input type="hidden" name="u_id" value="<?php echo $user['u_id']; ?>">
        <div class="mb-3">
            <label for="u_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="u_name" name="u_name" value="<?php echo $user['u_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="patient" <?php echo ($user['role'] == 'patient') ? 'selected' : ''; ?>>Patient</option>
                <option value="doctor" <?php echo ($user['role'] == 'doctor') ? 'selected' : ''; ?>>Doctor</option>
                <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="1" <?php echo ($user['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?php echo ($user['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>