<?php
include "navbar.php";

// Redirect to login if user is not logged in
if (!isset($_SESSION['USER_LOGIN']) || $_SESSION['USER_LOGIN'] != 'yes') {
    header("Location: login.php");
    exit();
}

include('connect.php'); // Include your database connection file

// Check if room_id is passed in the URL
if (!isset($_GET['room_id'])) {
    header("Location: roombooking.php");
    exit();
}

$room_id = intval($_GET['room_id']); // Sanitize the room_id

// Fetch room details from the database
$sql = "SELECT * FROM rooms WHERE room_id = $room_id";
$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>
            alert('Room not found.');
            window.location.href = 'roombooking.php';
          </script>";
    exit();
}

$room = mysqli_fetch_assoc($result); // Fetch room data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details | MedStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
        }

        .room-details-container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            gap: 20px;
        }

        .room-image {
            flex: 1;
        }

        .room-image img {
            width: 100%;
            border-radius: 10px;
            height: 400px;
            object-fit: cover;
        }

        .room-info {
            flex: 2;
        }

        .room-info h1 {
            color: #2E7D32;
            margin-bottom: 20px;
        }

        .room-info p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }

        .room-info .facilities {
            margin-top: 20px;
            font-weight: 500;
        }

        .room-info .availability {
            font-weight: 600;
            color: <?php echo $room['availability'] == 'Available' ? '#00C851' : '#ff4444'; ?>;
        }

        .room-info .price {
            font-size: 20px;
            font-weight: 600;
            color: #2E7D32;
        }

        .booking-form .row {
            margin-bottom: 15px;
        }

        .booking-form label {
            font-weight: 500;
        }

        .book-button {
            background: #2E7D32;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
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
    <div class="room-details-container" style="margin-top: 110px;">
        <!-- Room Image -->
        <div class="room-image">
            <img src="images/service/<?php echo $room['room_image']; ?>" alt="<?php echo $room['room_name']; ?>">
        </div>

        <!-- Room Details and Booking Form -->
        <div class="room-info">
            <h1><?php echo $room['room_name']; ?></h1>
            <p><strong>Type:</strong> <?php echo $room['room_type']; ?></p>
            <p><strong>Beds:</strong> <?php echo $room['beds']; ?></p>
            <p><strong>Availability:</strong> <span class="availability"><?php echo $room['availability']; ?></span></p>
            <p><strong>Facilities:</strong> <span class="facilities"><?php echo $room['facilities']; ?></span></p>
            <p><strong>Price:</strong> <span class="price">₹<?php echo $room['price']; ?> per night</span></p>

            <!-- Booking Form -->
            <form action="process_booking.php" method="POST" class="booking-form">
                <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
                <div class="row">
                    <div class="col-md-6">
                        <label for="check_in_date">Check-In Date:</label>
                        <input type="date" id="check_in_date" name="check_in_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="check_out_date">Check-Out Date:</label>
                        <input type="date" id="check_out_date" name="check_out_date" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="patient_name">Patient Name:</label>
                        <input type="text" id="patient_name" name="patient_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="phone_no">Phone Number:</label>
                        <input type="text" id="phone_no" name="phone_no" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-6 mb-3">
    <label for="state" class="form-label">State</label>
    <input list="states" class="form-control" name="state" placeholder="Type to search..." required>
    <datalist id="states">
        <option value="Andhra Pradesh">
        <option value="Arunachal Pradesh">
        <option value="Assam">
        <option value="Bihar">
        <option value="Chhattisgarh">
        <option value="Goa">
        <option value="Gujarat">
        <option value="Haryana">
        <option value="Himachal Pradesh">
        <option value="Jharkhand">
        <option value="Karnataka">
        <option value="Kerala">
        <option value="Madhya Pradesh">
        <option value="Maharashtra">
        <option value="Manipur">
        <option value="Meghalaya">
        <option value="Mizoram">
        <option value="Nagaland">
        <option value="Odisha">
        <option value="Punjab">
        <option value="Rajasthan">
        <option value="Sikkim">
        <option value="Tamil Nadu">
        <option value="Telangana">
        <option value="Tripura">
        <option value="Uttar Pradesh">
        <option value="Uttarakhand">
        <option value="West Bengal">
        <option value="Andaman and Nicobar Islands">
        <option value="Chandigarh">
        <option value="Dadra and Nagar Haveli and Daman and Diu">
        <option value="Lakshadweep">
        <option value="Delhi">
        <option value="Puducherry">
        <option value="Ladakh">
        <option value="Jammu and Kashmir">
    </datalist>
</div>

                    <div class="col-md-6">
                        <label for="flat_house_no">Flat/House No:</label>
                        <input type="text" id="flat_house_no" name="flat_house_no" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="pin_no">Pincode:</label>
                        <input type="text" id="pin_no" name="pin_no" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="book-button">Confirm Booking</button>
            </form>
        </div>
    </div>
</body>
</html>