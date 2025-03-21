<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');

if (isset($_POST['add_room'])) {
    $room_name = $_POST['room_name'];
    $room_type = $_POST['room_type'];
    $beds = $_POST['beds'];
    $availability = $_POST['availability'];

    $sql = "INSERT INTO rooms (room_name, room_type, beds, availability) 
            VALUES ('$room_name', '$room_type', $beds, '$availability')";
    if ($con->query($sql)) {
        echo "<script>alert('Room added successfully!'); window.location.href='manage_rooms.php';</script>";
    } else {
        echo "<script>alert('Error adding room!');</script>";
    }
}
?>

<div class="main-content animated">
    <h1 class="mb-4">Add New Room</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="room_name" class="form-label">Room Name</label>
            <input type="text" class="form-control" id="room_name" name="room_name" required>
        </div>
        <div class="mb-3">
            <label for="room_type" class="form-label">Room Type</label>
            <input type="text" class="form-control" id="room_type" name="room_type" required>
        </div>
        <div class="mb-3">
            <label for="beds" class="form-label">Beds</label>
            <input type="number" class="form-control" id="beds" name="beds" required>
        </div>
        <div class="mb-3">
            <label for="availability" class="form-label">Availability</label>
            <select class="form-control" id="availability" name="availability" required>
                <option value="available">Available</option>
                <option value="booked">Booked</option>
            </select>
        </div>
        <button type="submit" name="add_room" class="btn btn-primary">Add Room</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>