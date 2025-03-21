<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_POST['add_medicine'])) {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO medicine (name, brand, price, quantity, expiry_date, status) 
            VALUES ('$name', '$brand', $price, $quantity, '$expiry_date', $status)";
    if ($con->query($sql)) {
        echo "<script>alert('Medicine added successfully!'); window.location.href='manage_medicine.php';</script>";
    } else {
        echo "<script>alert('Error adding medicine!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Add New Medicine</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="brand" class="form-label">Brand</label>
            <input type="text" class="form-control" id="brand" name="brand" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="mb-3">
            <label for="expiry_date" class="form-label">Expiry Date</label>
            <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <button type="submit" name="add_medicine" class="btn btn-primary">Add Medicine</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>