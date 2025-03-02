<?php
include 'navbar.php';
// Redirect to login if user is not logged in
if (!isset($_SESSION['USER_LOGIN']) || $_SESSION['USER_LOGIN'] != 'yes') {
    header("Location: login.php");
    exit();
}

include('connect.php'); // Include your database connection file

$u_id = $_SESSION['uid']; // Get the logged-in user's ID
$msg = ''; // Variable to store success/error messages

// Fetch user data
$sql = "SELECT * FROM users WHERE u_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $u_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Default profile picture if none is set
$profile_pic = !empty($user['u_img']) ? $user['u_img'] : 'images/profilepic/pic.jpg';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u_name = $_POST['u_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $landmark = $_POST['landmark'];
    $flat_house_no = $_POST['flat_house_no'];

    // Handle profile picture upload
    if ($_FILES['u_img']['error'] == 0) {
        $target_dir = "images/profilepic/";
        $target_file = $target_dir . basename($_FILES['u_img']['name']);
        if (move_uploaded_file($_FILES['u_img']['tmp_name'], $target_file)) {
            $profile_pic = $target_file;
        } else {
            $msg = "Error uploading profile picture.";
        }
    }

    // Update user data in the database
    $sql = "UPDATE users SET u_name = ?, email = ?, phone = ?, address = ?, state = ?, landmark = ?, flat_house_no = ?, u_img = ? WHERE u_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssssssi", $u_name, $email, $phone, $address, $state, $landmark, $flat_house_no, $profile_pic, $u_id);

    if ($stmt->execute()) {
        $msg = "Profile updated successfully!";
    } else {
        $msg = "Error updating profile: " . $stmt->error;
    }
}
?>

    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            display: flex;
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #26988c;
            padding: 30px;
            color: #fff;
        }

        .sidebar h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 12px 16px;
            margin: 8px 0;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .sidebar ul li:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar ul li.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 500;
        }

        /* Profile Settings */
        .profile-settings {
            flex: 1;
            padding: 40px;
        }

        .profile-settings h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .profile-pic-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #26988c;
            cursor: pointer;
            transition: transform 0.3s, border-color 0.3s;
        }

        .profile-pic:hover {
            transform: scale(1.05);
            border-color: #1f7a6e;
        }

        .profile-pic-upload {
            margin-top: 10px;
        }

        .profile-pic-upload button {
            background: #26988c;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .profile-pic-upload button:hover {
            background: #1f7a6e;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #26988c;
            outline: none;
        }

        .form-group input:disabled {
            background: #f9f9f9;
            color: #777;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-edit {
            background: #26988c;
            color: #fff;
        }

        .btn-edit:hover {
            background: #1f7a6e;
        }

        .btn-save {
            background: #28a745;
            color: #fff;
            display: none;
        }

        .btn-save:hover {
            background: #218838;
        }

        .btn-cancel {
            background: #e74c3c;
            color: #fff;
        }

        .btn-cancel:hover {
            background: #c0392b;
        }

        /* Message Styling */
        .message {
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container" style="margin-top: 15px;">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>LEARNING ROBO</h2>
            <ul>
                <li class="active">Profile</li>
                <li>Notifications</li>
                <li>Billing Info</li>
                <li>General</li>
            </ul>
        </aside>

        <!-- Profile Settings -->
        <main class="profile-settings">
            <h2>Profile Settings</h2>
            <?php if (!empty($msg)): ?>
                <div class="message <?php echo strpos($msg, 'successfully') !== false ? 'success' : 'error'; ?>">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <!-- Profile Picture -->
                <div class="profile-pic-container">
                    <img src="<?php echo $profile_pic; ?>" id="profilePreview" class="profile-pic" onclick="document.getElementById('u_img').click()">
                    <div class="profile-pic-upload">
                        <input type="file" name="u_img" id="u_img" accept="image/*" style="display: none;" onchange="previewImage()">
                        <button type="button">Change Profile Picture</button>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="u_name" value="<?php echo $user['u_name']; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo $user['phone']; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo $user['address']; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state" value="<?php echo $user['state']; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Landmark</label>
                    <input type="text" name="landmark" value="<?php echo $user['landmark']; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>House No</label>
                    <input type="text" name="flat_house_no" value="<?php echo $user['flat_house_no']; ?>" disabled>
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <button type="button" id="editBtn" class="btn btn-edit">Edit Profile</button>
                    <button type="submit" id="saveBtn" class="btn btn-save">Save Changes</button>
                </div>
            </form>
        </main>
    </div>
    <?php
include 'footer.php';?>
    <script>
        // Enable form fields when Edit button is clicked
        document.getElementById('editBtn').addEventListener('click', function() {
            document.querySelectorAll('input[type="text"], input[type="email"]').forEach(input => input.disabled = false);
            this.style.display = 'none';
            document.getElementById('saveBtn').style.display = 'inline-block';
        });

        // Preview profile picture
        function previewImage() {
            let file = document.getElementById('u_img').files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>