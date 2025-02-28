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

<div class="product-container" style="margin-top:60px;">
    <form action="checkout.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <input type="hidden" id="price" value="<?php echo $product['price']; ?>">
        <input type="hidden" name="total_price" id="hidden_total_price">
        <input type="hidden" name="flat_house_no" value="<?php echo $user['flat_house_no']; ?>">
        <input type="hidden" name="landmark" value="<?php echo $user['landmark']; ?>">
        <input type="hidden" name="address" value="<?php echo $user['address']; ?>">
        <input type="hidden" name="state" value="<?php echo $user['state']; ?>">
        <input type="hidden" name="pin_no" value="<?php echo $user['pin_no']; ?>">

        <table>
            <thead>
                <tr style="text-align: center;">
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color: #223a66;color:white">
                    <td><img src="product/<?php echo $product['image']; ?>" alt="Medicine"></td>
                    <td><strong><?php echo $product['name']; ?></strong></td>
                    <td><?php echo $product['description']; ?></td>
                    <td>Rs <?php echo number_format($product['price'], 2); ?></td>
                    <td>
                        <div class="quantity-controls">
                            <button type="button" onclick="decreaseQuantity()">-</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" readonly onchange="updateTotal()" style="width: 40px; text-align: center;">
                            <button type="button" onclick="increaseQuantity()">+</button>
                        </div>
                    </td>
                    <td class="total-section"><span id="total_price">Rs <?php echo number_format($product['price'], 2); ?></span></td>
                </tr>
            </tbody>
        </table>

        <!-- Payment Method Selection -->
        <h3>Select Payment Method:</h3>
        <label><input type="radio" name="payment" value="cod" onclick="togglePayment('cod')"> Cash on Delivery</label>
        <label><input type="radio" name="payment" value="online" onclick="togglePayment('online')"> Online Payment</label>



        <!-- Online Payment Modal -->
        <div id="onlinePaymentModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('onlinePaymentModal')">&times;</span>
                <h3>Online Payment Details</h3>
                <p>Pay using Paytm or Google Pay</p>
                <p>UPI ID: example@upi</p>
                <p>After payment, enter Transaction ID below:</p>
                <input type="text" name="transaction_id" placeholder="Transaction ID">
                <button type="button" onclick="closeModal('onlinePaymentModal')">Confirm</button>
            </div>
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
                <button type="button" class="add-address-btn" onclick="openModal('addressModal')">+ Add Address</button>
            <?php } ?>
        </div>


        <!-- Address Modal -->
        <!-- Address Modal -->
        <?php if (!$hasAddress) { ?>
            <div id="addressModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('addressModal')">&times;</span>
                    <h3>Enter Delivery Address</h3>
                    <input type="text" id="flat_house_no" placeholder="Flat/House No" required>
                    <input type="text" id="landmark" placeholder="Landmark" required>
                    <input type="text" id="address" placeholder="Full Address" required>
                    <input type="text" id="state" placeholder="State" required>
                    <input type="text" id="pin_no" placeholder="Pincode" required>
                    <button type="button" id="saveAddressBtn">Save Address</button>
                </div>
            </div>
        <?php } ?>



        <button type="submit" name="submit" class="btn">Proceed to Checkout</button>
    </form>
</div>



<?php
include 'footer.php';

?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let hasAddress = <?php echo json_encode($hasAddress); ?>;
        if (hasAddress) {
            let modal = document.getElementById("addressModal");
            if (modal) {
                modal.style.display = "none";
            }
        }
    });
</script>

<!-- JavaScript -->
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
        if (method === 'online') {
            openModal('onlinePaymentModal');
        }
    }

    function openModal(id) {
        document.getElementById(id).style.display = "block";
    }

    function closeModal(id) {
        document.getElementById(id).style.display = "none";
    }
</script>
<script>
    document.getElementById("saveAddressBtn").addEventListener("click", function() {
        let flat_house_no = document.getElementById("flat_house_no").value;
        let landmark = document.getElementById("landmark").value;
        let address = document.getElementById("address").value;
        let state = document.getElementById("state").value;
        let pin_no = document.getElementById("pin_no").value;

        // Check if fields are filled
        if (!flat_house_no || !landmark || !address || !state || !pin_no) {
            alert("Please fill in all required fields.");
            return;
        }

        // Send data via AJAX
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "save_address.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let response = xhr.responseText.trim();
                if (response === "success") {
                    alert("Address saved successfully!");
                    closeModal('addressModal'); // Close modal after success
                    location.reload(); // Reload page to show saved address
                } else {
                    alert("Error saving address. Please try again.");
                }
            }
        };

        xhr.send("flat_house_no=" + flat_house_no + "&landmark=" + landmark + "&address=" + address + "&state=" + state + "&pin_no=" + pin_no);
    });
</script>


<style>
    /* Address Section Styling */
    .address-container {
        margin-top: 15px;
        padding: 15px;
    }

    .address-box {
        background: #f9f9f9;
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        font-size: 16px;
        max-width: 400px;
    }

    .address-box p {
        margin: 5px 0;
        color: #333;
    }

    /* Add Address Button */
    .add-address-btn {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s;
    }

    .add-address-btn:hover {
        background: #0056b3;
    }

    .product-container {
        max-width: 900px;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid #ddd;
        text-align: center;
        vertical-align: middle;
    }

    img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 5px;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .quantity-controls button {
        background: #007bff;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 5px;
    }

    .total-section {
        font-size: 18px;
        font-weight: bold;
    }

    .btn {
        background: #28a745;
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 10px;
    }

    /* Updated Modal Styling */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        /* Darker transparent background */
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .modal.show {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background: white;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        width: 90%;
        max-width: 400px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.3s ease-in-out;
        position: relative;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        color: #333;
        transition: color 0.2s ease;
    }

    .close:hover {
        color: red;
    }

    button {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 16px;
        transition: background 0.3s;
    }

    button:hover {
        background: #0056b3;
    }

    /* Fade-in animation */
    @keyframes fadeIn {
        from {
            transform: scale(0.9);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>
<script>
    function openModal(id) {
        let modal = document.getElementById(id);
        modal.classList.add("show"); // Apply 'show' class to make it visible
    }

    function closeModal(id) {
        let modal = document.getElementById(id);
        modal.classList.remove("show"); // Remove 'show' class to hide it
    }
</script>