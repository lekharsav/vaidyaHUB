<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $medicine_id = $_GET['id'];
    $sql = "SELECT * FROM medicine WHERE id = $medicine_id";
    $result = $con->query($sql);
    $medicine = $result->fetch_assoc();
}

if (isset($_POST['update_medicine'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    $sql = "UPDATE medicine SET name = '$name', brand = '$brand', price = $price, quantity = $quantity, expiry_date = '$expiry_date', status = $status WHERE id = $id";
    if ($con->query($sql)) {
        echo "<script>alert('Medicine updated successfully!'); window.location.href='manage_medicine.php';</script>";
    } else {
        echo "<script>alert('Error updating medicine!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Edit Medicine</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $medicine['id']; ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $medicine['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="brand" class="form-label">Brand</label>
            <input type="text" class="form-control" id="brand" name="brand" value="<?php echo $medicine['brand']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo $medicine['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $medicine['quantity']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="expiry_date" class="form-label">Expiry Date</label>
            <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?php echo $medicine['expiry_date']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="1" <?php echo ($medicine['status'] == 1 ? 'selected' : ''); ?>>Active</option>
                <option value="0" <?php echo ($medicine['status'] == 0 ? 'selected' : ''); ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" name="update_medicine" class="btn btn-primary">Update Medicine</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>