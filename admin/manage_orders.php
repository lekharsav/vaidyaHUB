<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_POST['update_status'])) {
    $o_id = $_POST['o_id'];
    $status = $_POST['status'];
    $sql = "UPDATE orders SET status = '$status' WHERE o_id = $o_id";
    if ($con->query($sql)) {
        echo "<script>alert('Order status updated successfully!'); window.location.href='manage_orders.php';</script>";
    } else {
        echo "<script>alert('Error updating order status!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Manage Orders</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT o.*, u.u_name AS user_name 
                        FROM orders o
                        JOIN users u ON o.u_id = u.u_id";
                $result = $con->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['user_name']}</td>
                            <td>{$row['total_price']}</td>
                            <td>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='o_id' value='{$row['o_id']}'>
                                    <select name='status' onchange='this.form.submit()'>
                                        <option value='Pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                        <option value='Processing' " . ($row['status'] == 'Processing' ? 'selected' : '') . ">Processing</option>
                                        <option value='Shipped' " . ($row['status'] == 'Shipped' ? 'selected' : '') . ">Shipped</option>
                                        <option value='Delivered' " . ($row['status'] == 'Delivered' ? 'selected' : '') . ">Delivered</option>
                                        <option value='Cancelled' " . ($row['status'] == 'Cancelled' ? 'selected' : '') . ">Cancelled</option>
                                    </select>
                                    <input type='hidden' name='update_status'>
                                </form>
                            </td>
                            <td>
                                <a href='edit_order.php?id={$row['o_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_order.php?id={$row['o_id']}' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>