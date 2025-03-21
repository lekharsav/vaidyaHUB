<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_POST['update_status'])) {
    $u_id = $_POST['u_id'];
    $status = $_POST['status'];
    $sql = "UPDATE users SET status = $status WHERE u_id = $u_id";
    if ($con->query($sql)) {
        echo "<script>alert('User status updated successfully!'); window.location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('Error updating user status!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Manage Users</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM users";
                $result = $con->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['u_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['role']}</td>
                            <td>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='u_id' value='{$row['u_id']}'>
                                    <select name='status' onchange='this.form.submit()'>
                                        <option value='1' " . ($row['status'] == 1 ? 'selected' : '') . ">Active</option>
                                        <option value='0' " . ($row['status'] == 0 ? 'selected' : '') . ">Inactive</option>
                                    </select>
                                    <input type='hidden' name='update_status'>
                                </form>
                            </td>
                            <td>
                                <a href='edit_user.php?id={$row['u_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_user.php?id={$row['u_id']}' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>