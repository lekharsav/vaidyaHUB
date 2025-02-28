<?php
include 'connect.php'; // Database connection
include 'navbar.php';
// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$u_id =  $_SESSION['uid']; // Logged-in user ID
$sql = "SELECT o.o_id, o.p_id, o.quantity, o.total_price, m.name, m.image 
        FROM orders o 
        JOIN medicine m ON o.p_id = m.id where o.u_id=$u_id
        ORDER BY o.order_date DESC"; 

$result = mysqli_query($con, $sql);
?>
<div class="container" style="margin-top: 40px;">
<table border="1" width="80%">
    <thead>
        <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><img src="product/<?php echo $row['image']; ?>"  width="70px" height="70px"></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>₹<?php echo number_format($row['total_price'], 2); ?> </td>
                <td><a href="view_order.php?o_id=<?php echo $row['o_id']; ?>" class="view-btn">View Order</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
</div>
<style>
    table {
    border-collapse: collapse;
    width: 100%;
    font-family: Arial, sans-serif;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background-color: #1faeaa;
    color: white;
}

tr:nth-child(even) {
    background-color: #f8f8f8;
}

tr:hover {
    background-color: #ddd;
}

img {
    border-radius: 5px;
    object-fit: cover;
}

.view-btn {
    text-decoration: none;
    padding: 8px 12px;
    background-color: #1faeaa;
    color: white;
    border-radius: 5px;
    transition: 0.3s;
}

.view-btn:hover {
    background-color: #148f87;
}

</style>