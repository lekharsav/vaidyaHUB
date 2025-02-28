<?php
session_start();
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
    <title>Buy Product</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-container {
            margin-top: 80px;
        }
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .quantity-controls button {
            background: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
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
                <h2><?php echo $product['name']; ?></h2>
                <p><?php echo $product['description']; ?></p>
                <p><strong>Price:</strong> Rs <?php echo number_format($product['price'], 2); ?></p>
                <form action="checkout.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" id="price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="total_price" id="hidden_total_price">
                    <input type="hidden" name="flat_house_no" value="<?php echo $user['flat_house_no']; ?>">
                    <input type="hidden" name="landmark" value="<?php echo $user['landmark']; ?>">
                    <input type="hidden" name="address" value="<?php echo $user['address']; ?>">
                    <input type="hidden" name="state" value="<?php echo $user['state']; ?>">
                    <input type="hidden" name="pin_no" value="<?php echo $user['pin_no']; ?>">

                    <!-- Quantity Controls -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label"><strong>Quantity:</strong></label>
                        <div class="quantity-controls">
                            <button type="button" onclick="decreaseQuantity()">-</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" readonly onchange="updateTotal()" style="width: 50px; text-align: center;">
                            <button type="button" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>
                    <p><strong>Total Price:</strong> <span id="total_price">Rs <?php echo number_format($product['price'], 2); ?></span></p>

                    <!-- Payment Method Selection -->
                    <h3>Select Payment Method:</h3>
                    <div class="mb-3">
                        <label><input type="radio" name="payment" value="cod" onclick="togglePayment('cod')"> Cash on Delivery</label>
                        <label><input type="radio" name="payment" value="online" onclick="togglePayment('online')"> Online Payment</label>
                    </div>

                    <!-- Online Payment Options -->
                    <div id="onlinePaymentOptions" style="display: none;">
                        <h4>Choose Online Payment Method:</h4>
                        <button type="button" class="btn btn-primary mb-2" onclick="showUPI()">UPI Payment</button>
                        <button type="button" class="btn btn-primary mb-2" onclick="showBankTransfer()">Bank Transfer</button>
                        <button type="button" class="btn btn-primary mb-2" onclick="showCardPayment()">Card Payment</button>
                    </div>

                    <!-- Delivery Address Section -->
                    <h3>Delivery Address:</h3>
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
                    <button type="submit" name="submit" class="btn btn-success mt-3">Proceed to Checkout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- UPI Payment Modal -->
    <div id="upiModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">UPI Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Scan the QR code below to pay via UPI:</p>
                    <img src="path_to_upi_barcode.png" alt="UPI Barcode" class="upi-barcode">
                    <p>Supported UPI IDs:</p>
                    <ul>
                        <li>example1@upi</li>
                        <li>example2@upi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Payment Modal -->
    <div id="cardModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Card Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="cardNumber" class="form-label">Card Number</label>
                            <input type="text" class="form-control" id="cardNumber" placeholder="Enter card number">
                        </div>
                        <div class="mb-3">
                            <label for="expiryDate" class="form-label">Expiry Date</label>
                            <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY">
                        </div>
                        <div class="mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cvv" placeholder="CVV">
                        </div>
                        <button type="submit" class="btn btn-primary">Pay Now</button>
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
            let price = parseFloat(document.getElementById("price").value);
            let qty = parseInt(document.getElementById("quantity").value);
            let total = price * qty;
            document.getElementById("total_price").innerText = "Rs " + total.toFixed(2);
            document.getElementById("hidden_total_price").value = total.toFixed(2);
        }

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

        function showUPI() {
            new bootstrap.Modal(document.getElementById('upiModal')).show();
        }

        function showCardPayment() {
            new bootstrap.Modal(document.getElementById('cardModal')).show();
        }

        function showBankTransfer() {
            alert("Bank transfer details will be sent to your email.");
        }

        document.getElementById("saveAddressBtn").addEventListener("click", function() {
            let flat_house_no = document.getElementById("flat_house_no").value;
            let landmark = document.getElementById("landmark").value;
            let address = document.getElementById("address").value;
            let state = document.getElementById("state").value;
            let pin_no = document.getElementById("pin_no").value;

            if (!flat_house_no || !landmark || !address || !state || !pin_no) {
                alert("Please fill in all required fields.");
                return;
            }

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "save_address.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let response = xhr.responseText.trim();
                    if (response === "success") {
                        alert("Address saved successfully!");
                        bootstrap.Modal.getInstance(document.getElementById('addressModal')).hide();
                        location.reload();
                    } else {
                        alert("Error saving address. Please try again.");
                    }
                }
            };

            xhr.send("flat_house_no=" + flat_house_no + "&landmark=" + landmark + "&address=" + address + "&state=" + state + "&pin_no=" + pin_no);
        });
    </script>
</body>
</html>
<?php
include 'footer.php';
?>