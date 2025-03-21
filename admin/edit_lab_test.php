<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $test_id = $_GET['id'];
    $sql = "SELECT * FROM lab_tests WHERE lt_id = $test_id";
    $result = $con->query($sql);
    $lab_test = $result->fetch_assoc();
}

if (isset($_POST['update_lab_test'])) {
    $lt_id = $_POST['lt_id'];
    $test_name = $_POST['test_name'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $sql = "UPDATE lab_tests SET test_name = '$test_name', price = $price, status = $status WHERE lt_id = $lt_id";
    if ($con->query($sql)) {
        echo "<script>alert('Lab test updated successfully!'); window.location.href='manage_lab_tests.php';</script>";
    } else {
        echo "<script>alert('Error updating lab test!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Edit Lab Test</h1>
    <form method="POST">
        <input type="hidden" name="lt_id" value="<?php echo $lab_test['lt_id']; ?>">
        <div class="mb-3">
            <label for="test_name" class="form-label">Test Name</label>
            <input type="text" class="form-control" id="test_name" name="test_name" value="<?php echo $lab_test['test_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo $lab_test['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="1" <?php echo ($lab_test['status'] == 1 ? 'selected' : ''); ?>>Active</option>
                <option value="0" <?php echo ($lab_test['status'] == 0 ? 'selected' : ''); ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" name="update_lab_test" class="btn btn-primary">Update Lab Test</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>