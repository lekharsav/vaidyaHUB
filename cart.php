<?php
session_start();
include 'connect.php';
include 'navbar.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .cart-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .cart-header {
            font-size: 22px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
        }
        .cart-table th {
            background-color: #007bff;
            color: white;
        }
        .qty-btn {
            width: 30px;
            height: 30px;
            border: none;
            background: #28a745;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .qty-btn:hover {
            background: #218838;
        }
        .remove-btn {
            color: red;
            font-size: 18px;
            cursor: pointer;
        }
        .checkout-btn {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .checkout-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="container py-4" style="margin-top: 50;">
    <div class="cart-container">
        <h2 class="cart-header">Your Shopping Cart</h2>

        <?php if (!empty($cart)) { ?>
            <table class="table table-bordered cart-table text-center mt-3">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $grandTotal = 0; ?>
                    <?php foreach ($_SESSION['cart'] as $id => $item) { ?>
    <tr id="cart-item-<?php echo $id; ?>">
        <td><?php echo htmlspecialchars($item['name']); ?></td>
        <td>Rs <?php echo number_format($item['price'], 2); ?></td>
        <td>
            <button class="qty-btn decrease" data-index="<?php echo $id; ?>">-</button>
            <input type="text" id="qty-<?php echo $id; ?>" value="<?php echo $item['quantity']; ?>" readonly style="width: 40px; text-align: center; border: none;">
            <button class="qty-btn increase" data-index="<?php echo $id; ?>">+</button>
        </td>
        <td class="total-price">Rs <span id="total-<?php echo $id; ?>"><?php echo number_format($item['price'] * $item['quantity'], 2); ?></span></td>
        <td><span class="remove-btn" data-index="<?php echo $id; ?>">❌</span></td>
    </tr>
<?php } ?>
                </tbody>
            </table>

            <h4 class="text-end mt-3">Grand Total: Rs <span id="grand-total"><?php echo number_format($grandTotal, 2); ?></span></h4>

            <div class="text-end mt-3">
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        <?php } else { ?>
            <p class="text-center text-muted mt-4">Your cart is empty.</p>
        <?php } ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php include 'footer.php'; ?>
<script>
$(document).ready(function () {
    $(".increase, .decrease").click(function () {
        let index = $(this).data("index");
        let change = $(this).hasClass("increase") ? 1 : -1;
        updateQuantity(index, change);
    });

    $(".remove-btn").click(function () {
        let index = $(this).data("index");
        removeFromCart(index);
    });
});

function updateQuantity(index, change) {
    let qtyElement = $("#qty-" + index);
    let totalElement = $("#total-" + index);
    let price = parseFloat($("#cart-item-" + index + " td:nth-child(2)").text().replace("Rs ", ""));
    let quantity = parseInt(qtyElement.val()) + change;
    if (quantity < 1) return;
    
    qtyElement.val(quantity);
    totalElement.text((price * quantity).toFixed(2));
    updateGrandTotal();
    
    $.post("update_cart.php", { index: index, quantity: quantity });
}

function updateGrandTotal() {
    let grandTotal = 0;
    $(".total-price span").each(function () {
        grandTotal += parseFloat($(this).text());
    });
    $("#grand-total").text(grandTotal.toFixed(2));
}

// function removeFromCart(index) {
//     $.ajax({
//         url: "remove_from_cart.php",
//         type: "POST",
//         data: { index: index },
//         dataType: "json", // Expect JSON response
//         success: function (response) {
//             if (response.status === "success") {
//                 $("#cart-item-" + index).fadeOut(500, function () {
//                     $(this).remove();
//                     updateGrandTotal();

//                     // Check if cart is empty
//                     if ($(".total-price").length === 0) {
//                         $(".cart-table").fadeOut();
//                         $(".text-end").fadeOut();
//                         $(".container").append('<p class="text-center text-muted">Your cart is empty.</p>');
//                     }
//                 });
//             } else {
//                 alert("Error: " + response.message);
//             }
//         },
//         error: function (xhr, status, error) {
//             console.error("AJAX Error:", status, error);
//             alert("Unable to remove item from cart. Please try again.");
//         }
//     });
// }
function removeFromCart(index) {
    $.ajax({
        url: "remove_from_cart.php",
        type: "POST",
        data: { index: index },
        dataType: "json", 
        success: function (response) {
            if (response.status === "success") {
                $("#cart-item-" + index).fadeOut(500, function () {
                    $(this).remove();
                    updateGrandTotal();

                    if ($(".total-price").length === 0) {
                        $(".cart-table").fadeOut();
                        $(".text-end").fadeOut();
                        $(".container").append('<p class="text-center text-muted">Your cart is empty.</p>');
                    }
                });
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            alert("Unable to remove item from cart. Please try again.");
        }
    });
}

function updateGrandTotal() {
    let grandTotal = 0;
    $(".total-price span").each(function () {
        grandTotal += parseFloat($(this).text());
    });
    $("#grand-total").text(grandTotal.toFixed(2));
}

</script>
