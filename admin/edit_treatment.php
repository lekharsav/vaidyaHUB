<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $treatment_id = $_GET['id'];
    $sql = "SELECT * FROM treatments WHERE treatment_id = $treatment_id";
    $result = $con->query($sql);
    $treatment = $result->fetch_assoc();
}

if (isset($_POST['update_treatment'])) {
    $treatment_id = $_POST['treatment_id'];
    $treatment_name = $_POST['treatment_name'];

    $sql = "UPDATE treatments SET treatment_name = '$treatment_name' WHERE treatment_id = $treatment_id";
    if ($con->query($sql)) {
        echo "<script>alert('Treatment updated successfully!'); window.location.href='manage_treatments.php';</script>";
    } else {
        echo "<script>alert('Error updating treatment!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Edit Treatment</h1>
    <form method="POST">
        <input type="hidden" name="treatment_id" value="<?php echo $treatment['treatment_id']; ?>">
        <div class="mb-3">
            <label for="treatment_name" class="form-label">Treatment Name</label>
            <input type="text" class="form-control" id="treatment_name" name="treatment_name" value="<?php echo $treatment['treatment_name']; ?>" required>
        </div>
        <button type="submit" name="update_treatment" class="btn btn-primary">Update Treatment</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>