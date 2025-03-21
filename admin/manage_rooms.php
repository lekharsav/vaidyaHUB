<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');
?>

<div class="main-content animated">
    <h1 class="mb-4">Manage Rooms</h1>
    <a href="add_room.php" class="btn btn-primary mb-4">Add New Room</a>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Room Type</th>
                    <th>Beds</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM rooms";
                $result = $con->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['room_name']}</td>
                            <td>{$row['room_type']}</td>
                            <td>{$row['beds']}</td>
                            <td>{$row['availability']}</td>
                            <td>
                                <a href='edit_room.php?id={$row['room_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_room.php?id={$row['room_id']}' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>