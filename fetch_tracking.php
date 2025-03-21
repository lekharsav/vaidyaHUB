<?php
include 'connect.php';

if (!isset($_GET['o_id'])) {
    echo "Invalid Order ID.";
    exit();
}

$o_id = intval($_GET['o_id']);
$sql = "SELECT status FROM orders WHERE o_id = $o_id";
$result = mysqli_query($con, $sql);
$order = mysqli_fetch_assoc($result);

$statuses = ["Ordered", "Packed", "In Transit", "Delivered"];
$current_index = array_search($order['status'], $statuses);
?>

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
