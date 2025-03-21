<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_POST['add_treatment'])) {
    $treatment_name = $_POST['treatment_name'];

    $sql = "INSERT INTO treatments (treatment_name) VALUES ('$treatment_name')";
    if ($con->query($sql)) {
        echo "<script>alert('Treatment added successfully!'); window.location.href='manage_treatments.php';</script>";
    } else {
        echo "<script>alert('Error adding treatment!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Add New Treatment</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="treatment_name" class="form-label">Treatment Name</label>
            <input type="text" class="form-control" id="treatment_name" name="treatment_name" required>
        </div>
        <button type="submit" name="add_treatment" class="btn btn-primary">Add Treatment</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>