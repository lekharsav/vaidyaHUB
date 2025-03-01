<?php
include "navbar.php";

// Redirect to login if user is not logged in
if (!isset($_SESSION['USER_LOGIN']) || $_SESSION['USER_LOGIN'] != 'yes') {
    header("Location: login.php");
    exit();
}

include('connect.php'); // Include your database connection file

// Fetch all rooms from the database
$sql = "SELECT * FROM rooms";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking | MedStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
        }

        .room-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .room-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            gap: 20px;
        }

        .room-image {
            flex: 1;
        }

        .room-image img {
            width: 100%;
            border-radius: 10px;
        }

        .room-details {
            flex: 2;
        }

        .room-details h2 {
            color: #2E7D32;
            margin-bottom: 10px;
        }

        .room-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .room-details .facilities {
            margin-top: 10px;
            font-weight: 500;
        }

        .room-details .availability {
            font-weight: 600;
            color: <?php echo $room['availability'] == 'Available' ? '#00C851' : '#ff4444'; ?>;
        }

        .room-details .price {
            font-size: 18px;
            font-weight: 600;
            color: #2E7D32;
        }

        .book-button {
            background: #2E7D32;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .book-button:hover {
            background: #1B5E20;
        }

        .book-button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="room-container" style="margin-top: 110px;">
        <h1>Room Booking</h1>

        <?php while ($room = mysqli_fetch_assoc($result)) { ?>
            <div class="room-card">
                <!-- Room Image -->
                <div class="room-image">
                    <img src="<?php echo $room['room_image']; ?>" alt="<?php echo $room['room_name']; ?>">
                </div>

                <!-- Room Details -->
                <div class="room-details">
                    <h2><?php echo $room['room_name']; ?></h2>
                    <p><strong>Type:</strong> <?php echo $room['room_type']; ?></p>
                    <p><strong>Beds:</strong> <?php echo $room['beds']; ?></p>
                    <p><strong>Availability:</strong> <span class="availability"><?php echo $room['availability']; ?></span></p>
                    <p><strong>Facilities:</strong> <span class="facilities"><?php echo $room['facilities']; ?></span></p>
                    <p><strong>Price:</strong> <span class="price">₹<?php echo $room['price']; ?> per night</span></p>

                    <!-- Book Button -->
                    <button class="book-button" <?php echo $room['availability'] == 'Booked' ? 'disabled' : ''; ?>>
                        <?php echo $room['availability'] == 'Available' ? 'Book Now' : 'Booked'; ?>
                    </button>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>