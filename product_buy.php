<?php
include 'connect.php'; // Include your database connection file
include 'navbar.php'; // Include your navbar file

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    die("You must be logged in to access this page.");
}

$u_id = $_SESSION['uid']; // Get the logged-in user's ID

// Check if the product ID is provided in the URL
if (!isset($_GET['id'])) {
    die("Product not found.");
}

$id = intval($_GET['id']); // Sanitize the product ID

// Fetch product details from the database
$query = "SELECT * FROM medicine WHERE id = $id";
$result = mysqli_query($con, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product not found.");
}

// Fetch user address details
$userQuery = "SELECT address, state, landmark, flat_house_no, pin_no FROM users WHERE u_id = $u_id";
$userResult = mysqli_query($con, $userQuery);
$user = mysqli_fetch_assoc($userResult);
$hasAddress = !empty($user['address']); // Check if the address is set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Product - <?php echo $product['name']; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #26988c;
            font-family: 'Arial', sans-serif;
        }
        .product-container {
            margin-top: 80px;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .quantity-controls button {
            background: #26988c;
            color: white;
            border: none;
            padding: 5px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        .quantity-controls input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 0 10px;
        }
        .modal-content {
            padding: 20px;
            border-radius: 10px;
        }
        .upi-barcode {
            max-width: 100%;
            height: auto;
            margin-top: 15px;
        }
        .address-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #26988c;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #26988c;
        }
        .btn-success {
            background-color: #26988c;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .payment-options {
            display: none;
            margin-top: 20px;
        }
        .payment-options button {
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="container product-container">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6">
                <img src="product/<?php echo $product['image']; ?>" alt="Medicine" class="product-image">
            </div>
            <!-- Product Details -->
            <div class="col-md-6">
                <h2 class="mb-4"><?php echo $product['name']; ?></h2>
                <p class="text-muted"><?php echo $product['description']; ?></p>
                <p class="h4"><strong>Price:</strong> Rs <?php echo number_format($product['price'], 2); ?></p>
                <form action="checkout.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <input type="hidden" name="total_price" id="hidden_total_price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="flat_house_no" value="<?php echo htmlspecialchars($user['flat_house_no']); ?>">
                    <input type="hidden" name="landmark" value="<?php echo htmlspecialchars($user['landmark']); ?>">
                    <input type="hidden" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                    <input type="hidden" name="state" value="<?php echo htmlspecialchars($user['state']); ?>">
                    <input type="hidden" name="pin_no" value="<?php echo htmlspecialchars($user['pin_no']); ?>">

                    <!-- Quantity Controls -->
                    <div class="mb-4">
                        <label for="quantity" class="form-label"><strong>Quantity:</strong></label>
                        <div class="quantity-controls d-flex align-items-center">
                            <button type="button" onclick="decreaseQuantity()">-</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" readonly onchange="updateTotal()">
                            <button type="button" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>
                    <p class="h5"><strong>Total Price:</strong> <span id="total_price">Rs <?php echo number_format($product['price'], 2); ?></span></p>

                    <!-- Payment Method Selection -->
                    <h3 class="mt-4">Select Payment Method:</h3>
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" value="cod" onclick="togglePayment('cod')">
                            <label class="form-check-label">Cash on Delivery</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" value="online" onclick="togglePayment('online')">
                            <label class="form-check-label">Online Payment</label>
                        </div>
                    </div>

                    <!-- Online Payment Options -->
                    <div id="onlinePaymentOptions" class="payment-options" style="color: #26988c;">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#upiModal">
                            UPI Payment
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bankTransferModal">
                            Bank Transfer
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#cardPaymentModal">
                            Card Payment
                        </button>
                    </div>

                    <!-- Delivery Address Section -->
                    <h3 class="mt-4">Delivery Address:</h3>
                    <div class="address-container">
                        <?php if ($hasAddress) { ?>
                            <div class="address-box">
                                <p><strong>Flat/House No:</strong> <?php echo $user['flat_house_no']; ?></p>
                                <p><strong>Landmark:</strong> <?php echo $user['landmark']; ?></p>
                                <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
                                <p><strong>State:</strong> <?php echo $user['state']; ?></p>
                                <p><strong>Pincode:</strong> <?php echo $user['pin_no']; ?></p>
                            </div>
                        <?php } else { ?>
                            <button type="button" class="btn btn-secondary" onclick="openModal('addressModal')">+ Add Address</button>
                        <?php } ?>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" name="submit" class="btn btn-success mt-4 w-100">Proceed to Checkout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- UPI Payment Modal -->
    <div class="modal fade" id="upiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">UPI Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Scan the QR code below to pay via UPI:</p>
                    <img src="images/pay.jpg" alt="UPI Barcode" class="upi-barcode">
                    <p>Supported UPI Apps: Google Pay, PhonePe, Paytm, etc.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bank Transfer Modal -->
    <div class="modal fade" id="bankTransferModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bank Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Enter your bank details:</p>
                    <form>
                        <div class="mb-3">
                            <label for="accountName" class="form-label">Account Name</label>
                            <input type="text" class="form-control" id="accountName" placeholder="Your Name">
                        </div>
                        <div class="mb-3">
                            <label for="accountNumber" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="accountNumber" placeholder="1234567890">
                        </div>
                        <div class="mb-3">
                            <label for="ifscCode" class="form-label">IFSC Code</label>
                            <input type="text" class="form-control" id="ifscCode" placeholder="ABCD0123456">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Payment Modal -->
    <div class="modal fade" id="cardPaymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Card Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Enter your card details:</p>
                    <form>
                        <div class="mb-3">
                            <label for="cardNumber" class="form-label">Card Number</label>
                            <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456">
                        </div>
                        <div class="mb-3">
                            <label for="expiryDate" class="form-label">Expiry Date</label>
                            <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY">
                        </div>
                        <div class="mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cvv" placeholder="123">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Modal -->
    <?php if (!$hasAddress) { ?>
        <div id="addressModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Enter Delivery Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addressForm">
                            <div class="mb-3">
                                <label for="flat_house_no" class="form-label">Flat/House No</label>
                                <input type="text" class="form-control" id="flat_house_no" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="number" class="form-control" id="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="landmark" class="form-label">Landmark</label>
                                <input type="text" class="form-control" id="landmark" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" required>
                            </div>
                            <div class="mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" required>
                            </div>
                            <div class="mb-3">
                                <label for="pin_no" class="form-label">Pincode</label>
                                <input type="text" class="form-control" id="pin_no" required>
                            </div>
                            <button type="button" id="saveAddressBtn" class="btn btn-primary">Save Address</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateTotal() {
            let price = parseFloat(<?php echo $product['price']; ?>); // Get price from PHP
            let qty = parseInt(document.getElementById("quantity").value) || 1;
            let total = price * qty;

            // Update displayed total price
            document.getElementById("total_price").innerText = "Rs " + total.toFixed(2);

            // Ensure the hidden input gets updated
            document.getElementById("hidden_total_price").value = total.toFixed(2);
        }

        // Initialize the total price on page load
        window.onload = updateTotal;

        function increaseQuantity() {
            let qtyInput = document.getElementById("quantity");
            qtyInput.value = parseInt(qtyInput.value) + 1;
            updateTotal();
        }

        function decreaseQuantity() {
            let qtyInput = document.getElementById("quantity");
            if (qtyInput.value > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
                updateTotal();
            }
        }

        function togglePayment(method) {
            let onlinePaymentOptions = document.getElementById("onlinePaymentOptions");
            if (method === 'online') {
                onlinePaymentOptions.style.display = 'block';
            } else {
                onlinePaymentOptions.style.display = 'none';
            }
        }
    </script>
</body>
</html>
<?php
include 'footer.php';
?>