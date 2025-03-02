<?php
<<<<<<< HEAD
session_start();
=======
include 'navbar.php';
>>>>>>> 9b10aed3cf50276c5ce763f6f84656647bb0d801

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

<<<<<<< HEAD
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
=======
// Default profile picture if none is set
$profile_pic = !empty($user['u_img']) ? $user['u_img'] : 'images/profilepic/pic.jpg';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
>>>>>>> 9b10aed3cf50276c5ce763f6f84656647bb0d801
    $u_name = $_POST['u_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $landmark = $_POST['landmark'];
    $flat_house_no = $_POST['flat_house_no'];
    $pin_no = $_POST['pin_no'];

<<<<<<< HEAD
    // Handle profile picture upload
    if ($_FILES['u_img']['error'] == 0) {
        $target_dir = "C:/xampp/htdocs/vaidya/vaidyaHUB/images/profilepic/"; // Folder to store uploaded images
        $target_file = $target_dir . basename($_FILES['u_img']['name']);
        move_uploaded_file($_FILES['u_img']['tmp_name'], $target_file);
        $u_img = "images/profilepic/" . basename($_FILES['u_img']['name']); // Relative path for database
    } else {
        $u_img = $user['u_img'] ?? ''; // Keep existing image if no new file is uploaded
    }

    // Update user data in the database
    $sql = "UPDATE users SET u_name = ?, email = ?, phone = ?, address = ?, state = ?, landmark = ?, flat_house_no = ?, pin_no = ?, u_img = ? WHERE u_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssssssi", $u_name, $email, $phone, $address, $state, $landmark, $flat_house_no, $pin_no, $u_img, $u_id);

    if ($stmt->execute()) {
        $msg = "Profile updated successfully!";
        // Refresh user data after update
        $sql = "SELECT * FROM users WHERE u_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $u_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
=======
    if ($_FILES['u_img']['error'] == 0) {
        $target_dir = "images/profilepic/";
        $target_file = $target_dir . basename($_FILES['u_img']['name']);
        move_uploaded_file($_FILES['u_img']['tmp_name'], $target_file);
        $profile_pic = $target_file;
    }

    $sql = "UPDATE users SET u_name = ?, email = ?, phone = ?, address = ?, state = ?, landmark = ?, flat_house_no = ?, u_img = ? WHERE u_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssssssi", $u_name, $email, $phone, $address, $state, $landmark, $flat_house_no, $profile_pic, $u_id);

    if ($stmt->execute()) {
        $msg = "Profile updated successfully!";
>>>>>>> 9b10aed3cf50276c5ce763f6f84656647bb0d801
    } else {
        $msg = "Error updating profile: " . $stmt->error;
    }
}
?>

<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | MedStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #72e7e4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .profile-box {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .profile-box h1 {
            color: #2E7D32;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
            color: #555;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .input-group input:focus {
            border-color: #2E7D32;
            outline: none;
            box-shadow: 0 0 5px rgba(46, 125, 50, 0.3);
        }

        .input-group input[type="file"] {
            padding: 8px;
        }

        button {
            width: 100%;
            background: #2E7D32;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background: #1B5E20;
            transform: translateY(-2px);
        }

        .error-message {
            color: #ff4444;
            font-size: 14px;
            margin-top: 10px;
            font-weight: 500;
        }

        .success-message {
            color: #00C851;
            font-size: 14px;
            margin-top: 10px;
            font-weight: 500;
        }

        .profile-picture-preview {
            margin-bottom: 20px;
        }

        .profile-picture-preview img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #2E7D32;
            object-fit: cover;
        }

        .upload-btn {
            margin-top: 10px;
        }

        .upload-btn label {
            background: #2E7D32;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .upload-btn label:hover {
            background: #1B5E20;
        }

        .edit-form {
            display: none; /* Hidden by default */
        }

        .profile-details {
            display: block; /* Visible by default */
        }
    </style>
</head>
<body>
    <div class="profile-box">
        <h1>My Profile</h1>

        <?php if ($msg != '') { ?>
            <p class="<?php echo (strpos($msg, 'successfully') === false ? 'error-message' : 'success-message'); ?>"><?php echo $msg; ?></p>
        <?php } ?>

        <!-- Display Mode -->
        <div class="profile-details" id="viewMode">
            <div class="profile-picture-preview">
                <img src="<?php echo !empty($user['u_img']) ? $user['u_img'] : 'https://via.placeholder.com/100'; ?>" alt="Profile Picture" id="profileImage">
            </div>

            <div class="input-group">
                <label>Name</label>
                <p><?php echo $user['u_name']; ?></p>
            </div>

            <div class="input-group">
                <label>Email</label>
                <p><?php echo $user['email']; ?></p>
            </div>

            <div class="input-group">
                <label>Phone</label>
                <p><?php echo $user['phone']; ?></p>
            </div>

            <div class="input-group">
                <label>Address</label>
                <p><?php echo $user['address']; ?></p>
            </div>

            <div class="input-group">
                <label>State</label>
                <p><?php echo $user['state']; ?></p>
            </div>

            <div class="input-group">
                <label>Landmark</label>
                <p><?php echo $user['landmark']; ?></p>
            </div>

            <div class="input-group">
                <label>Flat/House No</label>
                <p><?php echo $user['flat_house_no']; ?></p>
            </div>

            <div class="input-group">
                <label>PIN Code</label>
                <p><?php echo $user['pin_no']; ?></p>
            </div>

            <button type="button" onclick="toggleEditMode()">Edit Profile</button>
        </div>

        <!-- Edit Mode -->
        <div class="edit-form" id="editMode">
            <form method="POST" enctype="multipart/form-data">
                <div class="profile-picture-preview">
                    <img src="<?php echo !empty($user['u_img']) ? $user['u_img'] : 'https://via.placeholder.com/100'; ?>" alt="Profile Picture" id="profileImage">
                </div>

                <div class="upload-btn">
                    <label for="u_img">Upload New Photo</label>
                    <input type="file" id="u_img" name="u_img" style="display: none;" onchange="previewImage(event)">
                </div>

                <div class="input-group">
                    <label for="u_name">Name</label>
                    <input type="text" id="u_name" name="u_name" value="<?php echo $user['u_name']; ?>" required>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>

                <div class="input-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
                </div>

                <div class="input-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo $user['address']; ?>" required>
                </div>

                <div class="input-group">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" value="<?php echo $user['state']; ?>" required>
                </div>

                <div class="input-group">
                    <label for="landmark">Landmark</label>
                    <input type="text" id="landmark" name="landmark" value="<?php echo $user['landmark']; ?>" required>
                </div>

                <div class="input-group">
                    <label for="flat_house_no">Flat/House No</label>
                    <input type="text" id="flat_house_no" name="flat_house_no" value="<?php echo $user['flat_house_no']; ?>" required>
                </div>

                <div class="input-group">
                    <label for="pin_no">PIN Code</label>
                    <input type="text" id="pin_no" name="pin_no" value="<?php echo $user['pin_no']; ?>" required>
                </div>

                <button type="submit">Save Changes</button>
                <button type="button" onclick="toggleEditMode()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- JavaScript for Toggle Edit Mode and Image Preview -->
    <script>
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

        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
=======
    <div class="container">
        <aside class="sidebar">
            <h2>LEARNING ROBO</h2>
            <ul>
                <li class="active">Profile</li>
                <li>Notifications</li>
                <li>Billing Info</li>
                <li>General</li>
            </ul>
        </aside>
        
        <main class="profile-settings">
        <form method="POST" enctype="multipart/form-data">
            <!-- <h2>Profile Settings</h2> -->
            <div style="text-align: center;">
            <img src="<?php echo $profile_pic; ?>" id="profilePreview" class="profile-pic">
            <input type="file" name="u_img" id="u_img" accept="image/*" style="display: none;" onchange="previewImage()">
            <br>
            <button type="button" onclick="document.getElementById('u_img').click()">Change Profile Picture</button>
        </div>

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


                <div class="button-group">
            <button type="button" id="editBtn" class="btn btn-edit">Edit</button>
            <button type="submit" id="saveBtn" class="btn btn-save">Save</button>
        </div>
                <!-- <div class="button-group">
                    <button type="submit" class="save-btn">Save Changes</button>
                    <button type="button" class="cancel-btn">Cancel</button>
                </div> -->
                </form>
        </main>
        
    </div>

<style>

.container {
    display: flex;
    width: 1200px;
    background-color: #eaf2ed;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}
.btn {
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-edit {
            background: #007bff;
            color: white;
        }
        .btn-edit:hover {
            background: #0056b3;
        }
        .btn-save {
            background: #28a745;
            color: white;
            display: none;
        }
        .btn-save:hover {
            background: #218838;
        }
    
.sidebar {
    width: 250px;
    background-color: #83a27d;
    padding: 20px;
    color: white;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 20px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    padding: 10px;
    cursor: pointer;
}

.sidebar ul li.active {
    font-weight: bold;
}

.profile-settings {
    flex: 1;
    padding: 40px;
}

.profile-settings h2 {
    margin-bottom: 20px;
}

.profile-pic-container {
    text-align: center;
    margin-bottom: 20px;
}

.profile-pic {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 3px solid #000;
}
.profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto;
            border: 4px solid #007bff;
        }
.form-group {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

.form-group label {
    width: 30%;
    font-weight: bold;
}

.form-group input {
    width: 65%;
    padding: 8px;
    /* border: 1px solid #ccc; */
    border-radius: 5px;
    border: none;
    outline: none;
}

.button-group {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}

.save-btn {
    background-color: #5cb85c;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
}

.cancel-btn {
    background-color: #777;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
}

</style>

<script>
    document.getElementById('editBtn').addEventListener('click', function() {
        document.querySelectorAll('input[type="text"], input[type="email"]').forEach(input => input.disabled = false);
        this.style.display = 'none';
        document.getElementById('saveBtn').style.display = 'inline-block';
    });
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
>>>>>>> 9b10aed3cf50276c5ce763f6f84656647bb0d801
