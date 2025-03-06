<?php
include 'connect.php';
include 'navbar.php';

$u_id = $_SESSION['uid']; // Assuming user is logged in

if (!isset($_GET['id'])) {
    die("Product not found.");
}

$id = intval($_GET['id']);
$query = "SELECT * FROM medicine WHERE id = $id";
$result = mysqli_query($con, $query);
$product = mysqli_fetch_assoc($result);

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
            background-color: #f8f9fa;
        }
        .product-container {
            margin-top: 80px;
            padding: 20px;
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
            background: #007bff;
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
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn-success:hover {
            background-color: #218838;
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
    <!-- Custom JS -->
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
            let onlineOptions = document.getElementById("onlinePaymentOptions");
            if (method === 'online') {
                onlineOptions.style.display = 'block';
            } else {
                onlineOptions.style.display = 'none';
            }
        }

        function openModal(modalId) {
            let modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("saveAddressBtn").addEventListener("click", function() {
                let flat_house_no = document.getElementById("flat_house_no").value.trim();
                let landmark = document.getElementById("landmark").value.trim();
                let phone = document.getElementById("phone").value.trim();
                let address = document.getElementById("address").value.trim();
                let state = document.getElementById("state").value.trim();
                let pin_no = document.getElementById("pin_no").value.trim();

                if (!flat_house_no || !landmark || !address || !state || !pin_no || !phone) {
                    alert("Please fill in all required fields.");
                    return;
                }

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "save_address.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            let response = xhr.responseText.trim();
                            if (response === "success") {
                                alert("Address saved successfully!");
                                
                                // Close the Bootstrap modal
                                let modalEl = document.getElementById('addressModal');
                                let modalInstance = bootstrap.Modal.getInstance(modalEl);
                                if (modalInstance) {
                                    modalInstance.hide();
                                }

                                // Reload the page after closing the modal
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            } else if (response === "invalid_phone") {
                                alert("Invalid phone number. Please enter a 10-digit phone number.");
                            } else {
                                alert("Error saving address. Please try again.");
                            }
                        } else {
                            alert("Server error. Please check your connection.");
                        }
                    }
                };

                // Send the form data
                xhr.send(`flat_house_no=${encodeURIComponent(flat_house_no)}&landmark=${encodeURIComponent(landmark)}&phone=${encodeURIComponent(phone)}&address=${encodeURIComponent(address)}&state=${encodeURIComponent(state)}&pin_no=${encodeURIComponent(pin_no)}`);
            });
        });
    </script>
</body>
</html>
<?php
include 'footer.php';
?>