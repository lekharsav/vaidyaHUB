<?php
include 'connect.php';
include 'navbar.php';

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['o_id'])) {
    echo "Invalid Order ID.";
    exit();
}

$o_id = intval($_GET['o_id']);
$sql = "SELECT * FROM orders WHERE o_id = $o_id";
$result = mysqli_query($con, $sql);
$order = mysqli_fetch_assoc($result);

$statuses = ["Ordered", "Packed", "In Transit", "Delivered"];

// Calculate elapsed time since order date
$order_date = strtotime($order['order_date']);
$current_time = time();
$time_elapsed = ($current_time - $order_date) / (60 * 60 * 24); // Convert to days

// Auto-update order status based on time
if ($time_elapsed >= 2) {
    $current_status = "Delivered";
} elseif ($time_elapsed >= 1.5) {
    $current_status = "In Transit";
} elseif ($time_elapsed >= 1) {
    $current_status = "Packed";
} else {
    $current_status = "Ordered";
}

$current_index = array_search($current_status, $statuses);

// Format dates
$order_date_formatted = date("d M Y", $order_date);
$expected_delivery = date("d M Y", strtotime($order['order_date'] . ' +2 days'));

?>


<div class="container">
    <div class="tracking-container">
        <h2 class="mb-3">Order Tracking</h2>
        <h5>Order ID: <strong>#<?php echo $order['o_id']; ?></strong></h5>

        <div class="mt-3">
            <p><strong>Order Date:</strong> <?php echo $order_date_formatted; ?></p>
            <p><strong>Expected Delivery:</strong> <?php echo $expected_delivery; ?></p>
        </div>

        <!-- Progress Bar -->
        <div class="progress mt-4">
            <div class="progress-bar 
                <?php echo ($current_index >= 3) ? 'bg-success' : (($current_index >= 2) ? 'bg-info' : (($current_index >= 1) ? 'bg-warning' : 'bg-danger')); ?>" 
                role="progressbar" 
                style="width: <?php echo (($current_index + 1) / 4) * 100; ?>%;" 
                aria-valuenow="<?php echo ($current_index + 1) * 25; ?>" 
                aria-valuemin="0" 
                aria-valuemax="100">
                <?php echo $statuses[$current_index]; ?>
            </div>
        </div>

        <!-- Step Indicators -->
        <div class="step-indicator mt-2">
            <?php foreach ($statuses as $index => $status) { ?>
                <span class="<?php echo ($index <= $current_index) ? 'text-primary' : 'text-muted'; ?>">
                    <?php echo $status; ?>
                </span>
            <?php } ?>
        </div>

    </div>
</div>

<?php include "footer.php"; ?>

<!-- Auto Refresh Tracking -->
<script>
function refreshTracking(o_id) {
    fetch('fetch_tracking.php?o_id=' + o_id)
    .then(response => response.text())
    .then(data => {
        // Update only progress bar and step indicators
        document.getElementById("progress-container").innerHTML = data;
    });
}

// Refresh tracking every 5 seconds
setInterval(() => {
    refreshTracking(<?php echo $o_id; ?>);
}, 5000);
</script>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .progress {
            height: 25px;
            background-color: #e9ecef;
            border-radius: 10px;
        }

        .progress-bar {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            line-height: 25px;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .tracking-container {
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
    </style>

