<?php
include 'connect.php';

if (!isset($_GET['o_id'])) {
    exit("Invalid Order ID.");
}

$o_id = intval($_GET['o_id']);
$sql = "SELECT * FROM orders WHERE o_id = $o_id";
$result = mysqli_query($con, $sql);
$order = mysqli_fetch_assoc($result);

$statuses = ["Ordered", "Packed", "In Transit", "Delivered"];

$order_date = strtotime($order['order_date']);
$current_time = time();
$time_elapsed = ($current_time - $order_date) / (60 * 60 * 24);

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

// Determine progress bar color
$progress_color = ($current_index >= 3) ? 'bg-success' : (($current_index >= 2) ? 'bg-info' : (($current_index >= 1) ? 'bg-warning' : 'bg-danger'));
$progress_width = (($current_index + 1) / 4) * 100;

?>

<!-- Update Progress Bar -->
<div class="progress-bar <?php echo $progress_color; ?>" role="progressbar" 
    style="width: <?php echo $progress_width; ?>%;" 
    aria-valuenow="<?php echo ($current_index + 1) * 25; ?>" 
    aria-valuemin="0" 
    aria-valuemax="100">
    <?php echo $statuses[$current_index]; ?>
</div>

<!-- Update Step Indicators -->
<?php foreach ($statuses as $index => $status) { ?>
    <span class="<?php echo ($index <= $current_index) ? 'text-primary' : 'text-muted'; ?>">
        <?php echo $status; ?>
    </span>
<?php } ?>
