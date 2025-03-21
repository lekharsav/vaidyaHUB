<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_POST['add_order'])) {
    $u_id = $_POST['u_id'];
    $total_price = $_POST['total_price'];
    $status = $_POST['status'];

    $sql = "INSERT INTO orders (u_id, total_price, status) 
            VALUES ($u_id, $total_price, '$status')";
    if ($con->query($sql)) {
        echo "<script>alert('Order added successfully!'); window.location.href='manage_orders.php';</script>";
    } else {
        echo "<script>alert('Error adding order!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Add New Order</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="u_id" class="form-label">User ID</label>
            <input type="number" class="form-control" id="u_id" name="u_id" required>
        </div>
        <div class="mb-3">
            <label for="total_price" class="form-label">Total Price</label>
            <input type="number" class="form-control" id="total_price" name="total_price" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Pending">Pending</option>
                <option value="Processing">Processing</option>
                <option value="Shipped">Shipped</option>
                <option value="Delivered">Delivered</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
        <button type="submit" name="add_order" class="btn btn-primary">Add Order</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
