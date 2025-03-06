<?php
include 'connect.php';
include "navbar.php";

// Secure Search Functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if ($search) {
    $stmt = $con->prepare("SELECT * FROM medicine WHERE name LIKE ? OR brand LIKE ? OR generic_name LIKE ? ORDER BY id DESC LIMIT 50");
    $searchTerm = "%$search%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $con->query("SELECT * FROM medicine ORDER BY id DESC LIMIT 50");
}
?>

<head>
    <title>Medicine List</title>
    
    <style>
    
        .medicine-card {
            transition: transform 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #ddd;
        }
        .medicine-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
        }
        .medicine-img-container {
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        .medicine-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .cart-btn {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 20px;
            color: green;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <h2 class="text-center mb-4 text-primary">Medicine List</h2>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <form method="GET" class="input-group shadow-sm rounded-pill overflow-hidden">
                <input class="form-control border-0 px-3" type="search" name="search" placeholder="🔍 Search for medicine..." 
                    value="<?php echo htmlspecialchars($search); ?>" 
                    style="border-top-left-radius: 25px; border-bottom-left-radius: 25px;">

                <button class="btn btn-primary px-4" type="submit" 
                    style="border-top-right-radius: 25px; border-bottom-right-radius: 25px;">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
    </div>


    <!-- Medicine Grid -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col">
                    <div class="card h-100 shadow-sm medicine-card">
                        <div class="medicine-img-container">
                            <img src="product/<?php echo htmlspecialchars($row['image']); ?>" class="medicine-img" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        </div>
                        <div class="card-body text-center">
                            <h6 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($row['name']); ?></h6>
                            <p class="card-text small"><strong>Brand:</strong> <?php echo htmlspecialchars($row['brand']); ?></p>
                            <p class="card-text small"><strong>Generic:</strong> <?php echo htmlspecialchars($row['generic_name']); ?></p>
                            <p class="card-text small"><strong>Dosage:</strong> <?php echo htmlspecialchars($row['dosage']); ?></p>
                          <p class="card-text text-success fw-bold">Rs <?php echo number_format($row['price'], 2, '.', ','); ?></p>
                            
                            <!-- Add to Cart Button -->
                            <button class="btn btn-outline-success btn-sm add-to-cart" data-id="<?php echo $row['id']; ?>" data-name="<?php echo htmlspecialchars($row['name']); ?>" data-price="<?php echo $row['price']; ?>">
                                🛒 Add to Cart
                            </button>

                            <!-- Buy Now Button -->
                        
                            <a href="product_buy.php?id=<?php echo $row['id']; ?>" class="btn  btn-sm btn-primary">Buy Now</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php else: ?>
            <p class="text-center text-muted">No medicines found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    updateCartCount();

    // Add to Cart Button Click
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", function() {
            let id = this.getAttribute("data-id");
            let name = this.getAttribute("data-name");
            let price = this.getAttribute("data-price");

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "add_to_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    updateCartCount(); // Update cart count after adding
                }
            };

            xhr.send("id=" + id + "&name=" + encodeURIComponent(name) + "&price=" + price + "&quantity=1");
        });
    });
});

function updateCartCount() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "cart_count.php", true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("cart-count").innerText = xhr.responseText;
        }
    };

    xhr.send();
}
</script>

<?php include 'footer.php'; ?>
</body>
</html>
