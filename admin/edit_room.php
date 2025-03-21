<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];
    $sql = "SELECT * FROM rooms WHERE room_id = $room_id";
    $result = $con->query($sql);
    $room = $result->fetch_assoc();
}

if (isset($_POST['update_room'])) {
    $room_id = $_POST['room_id'];
    $room_name = $_POST['room_name'];
    $room_type = $_POST['room_type'];
    $beds = $_POST['beds'];
    $availability = $_POST['availability'];

    $sql = "UPDATE rooms SET room_name = '$room_name', room_type = '$room_type', beds = $beds, availability = '$availability' WHERE room_id = $room_id";
    if ($con->query($sql)) {
        echo "<script>alert('Room updated successfully!'); window.location.href='manage_rooms.php';</script>";
    } else {
        echo "<script>alert('Error updating room!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Edit Room</h1>
    <form method="POST">
        <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
        <div class="mb-3">
            <label for="room_name" class="form-label">Room Name</label>
            <input type="text" class="form-control" id="room_name" name="room_name" value="<?php echo $room['room_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="room_type" class="form-label">Room Type</label>
            <input type="text" class="form-control" id="room_type" name="room_type" value="<?php echo $room['room_type']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="beds" class="form-label">Beds</label>
            <input type="number" class="form-control" id="beds" name="beds" value="<?php echo $room['beds']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="availability" class="form-label">Availability</label>
            <select class="form-control" id="availability" name="availability" required>
                <option value="available" <?php echo ($room['availability'] == 'available') ? 'selected' : ''; ?>>Available</option>
                <option value="booked" <?php echo ($room['availability'] == 'booked') ? 'selected' : ''; ?>>Booked</option>
            </select>
        </div>
        <button type="submit" name="update_room" class="btn btn-primary">Update Room</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>