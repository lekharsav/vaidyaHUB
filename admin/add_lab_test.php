<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_POST['add_lab_test'])) {
    $test_name = $_POST['test_name'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $sql = "INSERT INTO lab_tests (test_name, price, status) VALUES ('$test_name', $price, $status)";
    if ($con->query($sql)) {
        echo "<script>alert('Lab test added successfully!'); window.location.href='manage_lab_tests.php';</script>";
    } else {
        echo "<script>alert('Error adding lab test!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Add New Lab Test</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="test_name" class="form-label">Test Name</label>
            <input type="text" class="form-control" id="test_name" name="test_name" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <button type="submit" name="add_lab_test" class="btn btn-primary">Add Lab Test</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>