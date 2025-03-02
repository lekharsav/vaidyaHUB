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
  

    if ($_FILES['u_img']['error'] == 0) {
        $target_dir = "images/profilepic/";
        $target_file = $target_dir . basename($_FILES['u_img']['name']);
        move_uploaded_file($_FILES['u_img']['tmp_name'], $target_file);
        $profile_pic = $target_file;
    }

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
