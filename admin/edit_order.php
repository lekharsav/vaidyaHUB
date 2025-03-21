<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $sql = "SELECT * FROM orders WHERE o_id = $order_id";
    $result = $con->query($sql);
    $order = $result->fetch_assoc();
}

if (isset($_POST['update_order'])) {
    $o_id = $_POST['o_id'];
    $u_id = $_POST['u_id'];
    $total_price = $_POST['total_price'];
    $status = $_POST['status'];

    $sql = "UPDATE orders SET u_id = $u_id, total_price = $total_price, status = '$status' WHERE o_id = $o_id";
    if ($con->query($sql)) {
        echo "<script>alert('Order updated successfully!'); window.location.href='manage_orders.php';</script>";
    } else {
        echo "<script>alert('Error updating order!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Edit Order</h1>
    <form method="POST">
        <input type="hidden" name="o_id" value="<?php echo $order['o_id']; ?>">
        <div class="mb-3">
            <label for="u_id" class="form-label">User ID</label>
            <input type="number" class="form-control" id="u_id" name="u_id" value="<?php echo $order['u_id']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="total_price" class="form-label">Total Price</label>
            <input type="number" class="form-control" id="total_price" name="total_price" value="<?php echo $order['total_price']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Processing" <?php echo ($order['status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                <option value="Shipped" <?php echo ($order['status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                <option value="Delivered" <?php echo ($order['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                <option value="Cancelled" <?php echo ($order['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" name="update_order" class="btn btn-primary">Update Order</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>