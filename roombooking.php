<?php
// Start output buffering to prevent headers already sent error
ob_start();

include "navbar.php";


// Redirect to login if user is not logged in
if (!isset($_SESSION['USER_LOGIN']) || $_SESSION['USER_LOGIN'] != 'yes') {
    header("Location: login.php");
    exit();
}

// Include necessary files

include('connect.php'); // Include your database connection file

// Fetch unique room types and facilities from the database
$room_types_query = "SELECT DISTINCT room_type FROM rooms";
$room_types_result = mysqli_query($con, $room_types_query);

$facilities_query = "SELECT DISTINCT facilities FROM rooms";
$facilities_result = mysqli_query($con, $facilities_query);

// Fetch filter values from the form
$room_type = isset($_GET['room_type']) ? $_GET['room_type'] : '';
$beds = isset($_GET['beds']) ? $_GET['beds'] : '';
$availability = isset($_GET['availability']) ? $_GET['availability'] : '';
$facilities = isset($_GET['facilities']) ? $_GET['facilities'] : '';
$price_order = isset($_GET['price_order']) ? $_GET['price_order'] : '';

// Build the SQL query with filters
$sql = "SELECT * FROM rooms WHERE 1=1";

if (!empty($room_type)) {
    $sql .= " AND room_type = '$room_type'";
}
if (!empty($beds)) {
    $sql .= " AND beds = $beds";
}
if (!empty($availability)) {
    $sql .= " AND availability = '$availability'";
}
if (!empty($facilities)) {
    $sql .= " AND facilities LIKE '%$facilities%'";
}
if (!empty($price_order)) {
    $sql .= " ORDER BY price $price_order";
}

$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking | MedStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
        }

        .room-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .filter-section {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            display: none; /* Hidden by default */
        }

        .room-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .room-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .room-image img {
            width: 100%;
            border-radius: 10px;
            height: 200px;
            object-fit: cover;
        }

        .room-details {
            margin-top: 15px;
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
            margin-top: 15px;
        }

        .book-button:hover {
            background: #1B5E20;
        }

        .book-button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .view-bookings-button {
            background: #26988c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }

        .view-bookings-button:hover {
            background: #1f7a6f;
        }

        .filter-toggle-button {
            background: #26988c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }

        .filter-toggle-button:hover {
            background: #1f7a6f;
        }
    </style>
</head>
<body>
    <div class="room-container" style="margin-top: 110px;">
        <h1>Room Booking</h1>

        <!-- Filter Toggle Button -->
        <button class="filter-toggle-button" onclick="toggleFilterSection()">
            Show Filters
        </button>

        <!-- Filter Section -->
        <div class="filter-section" id="filterSection">
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-3">
                        <label for="room_type">Room Type:</label>
                        <select id="room_type" name="room_type" class="form-control">
                            <option value="">All</option>
                            <?php while ($row = mysqli_fetch_assoc($room_types_result)): ?>
                                <option value="<?php echo $row['room_type']; ?>" <?php echo $room_type == $row['room_type'] ? 'selected' : ''; ?>>
                                    <?php echo $row['room_type']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="beds">Beds:</label>
                        <input type="number" id="beds" name="beds" class="form-control" value="<?php echo $beds; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="availability">Availability:</label>
                        <select id="availability" name="availability" class="form-control">
                            <option value="">All</option>
                            <option value="Available" <?php echo $availability == 'Available' ? 'selected' : ''; ?>>Available</option>
                            <option value="Booked" <?php echo $availability == 'Booked' ? 'selected' : ''; ?>>Booked</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="facilities">Facilities:</label>
                        <select id="facilities" name="facilities" class="form-control">
                            <option value="">All</option>
                            <?php while ($row = mysqli_fetch_assoc($facilities_result)): ?>
                                <option value="<?php echo $row['facilities']; ?>" <?php echo $facilities == $row['facilities'] ? 'selected' : ''; ?>>
                                    <?php echo $row['facilities']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="price_order">Price:</label>
                        <select id="price_order" name="price_order" class="form-control">
                            <option value="">None</option>
                            <option value="ASC" <?php echo $price_order == 'ASC' ? 'selected' : ''; ?>>Low to High</option>
                            <option value="DESC" <?php echo $price_order == 'DESC' ? 'selected' : ''; ?>>High to Low</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Apply Filters</button>
            </form>
        </div>

        <!-- Button to view bookings -->
        <a href="user_bookings.php">
            <button class="view-bookings-button">
                View Your Bookings
            </button>
        </a>

        <!-- Room Grid -->
        <div class="room-grid">
            <?php while ($room = mysqli_fetch_assoc($result)) { ?>
                <div class="room-card">
                    <!-- Room Image -->
                    <div class="room-image">
                        <img src="images/service/<?php echo $room['room_image']; ?>" alt="<?php echo $room['room_name']; ?>">
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
                        <a href="room_details.php?room_id=<?php echo $room['room_id']; ?>">
                            <button class="book-button" <?php echo $room['availability'] == 'Booked' ? 'disabled' : ''; ?>>
                                <?php echo $room['availability'] == 'Available' ? 'Book Now' : 'Booked'; ?>
                            </button>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        // Function to toggle the filter section
        function toggleFilterSection() {
            const filterSection = document.getElementById('filterSection');
            if (filterSection.style.display === 'none') {
                filterSection.style.display = 'block';
            } else {
                filterSection.style.display = 'none';
            }
        }
    </script>
</body>
</html>

<?php
// End output buffering and send output to the browser
ob_end_flush();
?>