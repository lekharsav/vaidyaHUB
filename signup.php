<?php
session_start();
include 'connect.php'; // Database connection

// Redirect to login if user is not logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$u_id = $_SESSION['uid']; // Logged-in user ID

// Fetch user details
$userQuery = "SELECT * FROM users WHERE u_id = ?";
$stmt = mysqli_prepare($con, $userQuery);
mysqli_stmt_bind_param($stmt, "i", $u_id);
mysqli_stmt_execute($stmt);
$userResult = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($userResult);

// Update user information
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $u_name = $_POST['u_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $landmark = $_POST['landmark'];
    $flat_house_no = $_POST['flat_house_no'];
    $pin_no = $_POST['pin_no'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format.";
    } else {
        // Update query
        $updateQuery = "UPDATE users 
                        SET u_name = ?, email = ?, phone = ?, address = ?, state = ?, landmark = ?, flat_house_no = ?, pin_no = ?
                        WHERE u_id = ?";
        $stmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ssssssssi", $u_name, $email, $phone, $address, $state, $landmark, $flat_house_no, $pin_no, $u_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $msg = "Profile updated successfully!";
            // Update session data
            $_SESSION['name'] = $u_name;
            $_SESSION['email'] = $email;
            // Refresh user data
            $user['u_name'] = $u_name;
            $user['email'] = $email;
            $user['phone'] = $phone;
            $user['address'] = $address;
            $user['state'] = $state;
            $user['landmark'] = $landmark;
            $user['flat_house_no'] = $flat_house_no;
            $user['pin_no'] = $pin_no;
        } else {
            $msg = "Failed to update profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | MedStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .profile-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2E7D32;
            font-weight: bold;
        }
        .profile-details {
            margin-bottom: 25px;
        }
        .profile-details p {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .profile-details strong {
            color: #333;
        }
        .edit-form {
            display: none; /* Hidden by default */
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ddd;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: #2E7D32;
            box-shadow: 0 0 5px rgba(46, 125, 50, 0.5);
        }
        .btn-primary {
            background-color: #2E7D32;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1B5E20;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .message {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>My Profile</h2>
        <?php if ($msg != '') { ?>
            <div class="message <?php echo strpos($msg, 'successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo $msg; ?>
            </div>
        <?php } ?>

        <!-- View Mode -->
        <div class="profile-details" id="viewMode">
            <p><strong>Name:</strong> <?php echo $user['u_name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
            <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
            <p><strong>State:</strong> <?php echo $user['state']; ?></p>
            <p><strong>Landmark:</strong> <?php echo $user['landmark']; ?></p>
            <p><strong>Flat/House No:</strong> <?php echo $user['flat_house_no']; ?></p>
            <p><strong>PIN Code:</strong> <?php echo $user['pin_no']; ?></p>
            <button class="btn btn-primary w-100" onclick="toggleEditMode()">Edit Profile</button>
        </div>

        <!-- Edit Mode -->
        <div class="edit-form" id="editMode">
            <form method="POST">
                <div class="form-group">
                    <label for="u_name">Full Name</label>
                    <input type="text" class="form-control" id="u_name" name="u_name" value="<?php echo $user['u_name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" class="form-control" id="state" name="state" value="<?php echo $user['state']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="landmark">Landmark</label>
                    <input type="text" class="form-control" id="landmark" name="landmark" value="<?php echo $user['landmark']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="flat_house_no">Flat/House No</label>
                    <input type="text" class="form-control" id="flat_house_no" name="flat_house_no" value="<?php echo $user['flat_house_no']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="pin_no">PIN Code</label>
                    <input type="text" class="form-control" id="pin_no" name="pin_no" value="<?php echo $user['pin_no']; ?>" required>
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary w-100">Save Changes</button>
                <button type="button" class="btn btn-secondary w-100 mt-3" onclick="toggleEditMode()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Toggle between view mode and edit mode
        function toggleEditMode() {
            const viewMode = document.getElementById('viewMode');
            const editMode = document.getElementById('editMode');
            if (viewMode.style.display === 'none') {
                viewMode.style.display = 'block';
                editMode.style.display = 'none';
            } else {
                viewMode.style.display = 'none';
                editMode.style.display = 'block';
            }
        }
    </script>
</body>
</html>