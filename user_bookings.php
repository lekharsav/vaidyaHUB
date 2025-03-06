<?php
include "navbar.php"; // Session is already included in navbar.php
include('connect.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>
            alert('You must be logged in to view your bookings.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

$u_id = $_SESSION['uid']; // Get the logged-in user's ID

// Fetch bookings for the logged-in user
$query = "SELECT rb.*, r.room_name, r.room_type, r.price, r.room_image 
          FROM room_bookings rb
          INNER JOIN rooms r ON rb.room_id = r.room_id
          WHERE rb.u_id = '$u_id'
          ORDER BY rb.booking_date DESC";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Error fetching bookings: " . mysqli_error($con));
}
?>

    <title>Your Room Bookings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
        }

        .booking-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .booking-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .booking-table th, .booking-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .booking-table th {
            background-color: #26988c;
            color: white;
        }

        .booking-table tr:hover {
            background-color: #f5f5f5;
        }

        .room-image img {
            width: 100px;
            height: 70px;
            border-radius: 10px;
            object-fit: cover;
        }

        .status-pending {
            color: #ffc107;
        }

        .status-confirmed {
            color: #28a745;
        }

        .status-cancelled {
            color: #dc3545;
        }
    </style>
    <div class="booking-container" style="margin-top: 110px;">
        <h1>Your Room Bookings</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Room Image</th>
                        <th>Room Name</th>
                        <th>Type</th>
                        <th>Check-In Date</th>
                        <th>Check-Out Date</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <div class="room-image">
                                    <img src="images/service/<?php echo $booking['room_image']; ?>" alt="<?php echo $booking['room_name']; ?>">
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['room_type']); ?></td>
                            <td><?php echo htmlspecialchars($booking['check_in_date']); ?></td>
                            <td><?php echo htmlspecialchars($booking['check_out_date']); ?></td>
                            <td>₹<?php echo htmlspecialchars($booking['price']); ?> per night</td>
                            <td>
                                <?php
                                $statusClass = '';
                                switch ($booking['status']) {
                                    case 'Pending':
                                        $statusClass = 'status-pending';
                                        break;
                                    case 'Confirmed':
                                        $statusClass = 'status-confirmed';
                                        break;
                                    case 'Cancelled':
                                        $statusClass = 'status-cancelled';
                                        break;
                                }
                                ?>
                                <span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($booking['status']); ?></span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </div>
<?php include'footer.php';?>