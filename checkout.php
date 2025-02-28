<?php

include 'connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    include 'connect.php';

    if (!isset($_SESSION['uid'])) {
        die("User not logged in.");
    }

    $u_id = $_SESSION['uid'];

    // Check if required fields are set
    if (!isset($_POST['id'], $_POST['quantity'], $_POST['total_price'], $_POST['payment'])) {
        die("Missing required fields.");
    }

    $p_id = intval($_POST['id']);
    $quantity = intval($_POST['quantity']);
    $total_price = floatval($_POST['total_price']);
    $payment_method = $_POST['payment']; // Fix: Ensure it exists before using it

    // For online payment, transaction ID must be set
    $transaction_id = ($payment_method == "online" && isset($_POST['transaction_id'])) ? $_POST['transaction_id'] : NULL;

    $order_date = date("Y-m-d H:i:s");
    $delivery_date = date("Y-m-d H:i:s", strtotime("+3 days")); // Estimated delivery in 3 days
    $status = "Pending";

    // Fetch user phone number
    $userQuery = "SELECT phone FROM users WHERE u_id = $u_id";
    $userResult = mysqli_query($con, $userQuery);
    $user = mysqli_fetch_assoc($userResult);
    $phone = $user['phone'] ?? '';

    // Address details from the form
    $flat_house_no = $_POST['flat_house_no'] ?? '';
    $landmark = $_POST['landmark'] ?? '';
    $address = $_POST['address'] ?? '';
    $state = $_POST['state'] ?? '';
    $pin_no = $_POST['pin_no'] ?? '';

    // Check if address fields are empty
    if (empty($flat_house_no) || empty($landmark) || empty($address) || empty($state) || empty($pin_no)) {
        die("Missing address details.");
    }

    // Insert order into the database
    $query = "INSERT INTO orders (u_id, p_id, quantity, total_price, payment_method, transaction_id, order_date, delivery_date, status, phone, flat_house_no, landmark, address, state, pin_no)
              VALUES ('$u_id', '$p_id', '$quantity', '$total_price', '$payment_method', '$transaction_id', '$order_date', '$delivery_date', '$status', '$phone', '$flat_house_no', '$landmark', '$address', '$state', '$pin_no')";

    if (mysqli_query($con, $query)) {
      echo '
<div>
<div>
    <span></span>
    <h2>Thank You!</h2>
    <p>Your order has been placed successfully.</p>
</div>
</div>';
    } else {
        echo "Error: " . mysqli_error($con);
    }

    mysqli_close($con);
}

?>


<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }
    .modal-content {
        background-color: #fff;
        padding: 20px;
        margin: 15% auto;
        width: 30%;
        text-align: center;
        border-radius: 10px;
    }
    .close {
        float: right;
        cursor: pointer;
        font-size: 20px;
    }
</style>
