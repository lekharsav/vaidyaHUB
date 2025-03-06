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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-settings-container { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; }
        .profile-settings-pic { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; }
        .profile-settings-msg { text-align: center; color: #26988c; margin-top: 10px; }
        .profile-settings-form .row { margin-bottom: 15px; }
        .profile-settings-form .row label { font-weight: bold; }
        .profile-settings-form .row input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .profile-settings-form .row input:disabled { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="profile-settings-container">
        <h2 class="text-center mb-4">Profile Settings</h2>
        <p id="profile-settings-msg" class="profile-settings-msg"></p>

        <div class="text-center mb-4">
            <img src="<?php echo $profile_pic; ?>" id="profilePreview" class="profile-settings-pic">
            <input type="file" name="u_img" id="u_img" accept="image/*" style="display: none;" onchange="previewImage()">
            <button type="button" class="btn btn-secondary mt-2" onclick="document.getElementById('u_img').click()"><i class="bi bi-camera"></i></button>
        </div>

        <form id="profileForm" class="profile-settings-form">
            <div class="row">
                <?php
                $fields = [
                    'u_name' => 'Full Name',
                    'email' => 'Email',
                    'phone' => 'Phone',
                    'address' => 'Address',
                    'state' => 'State',
                    'landmark' => 'Landmark',
                    'flat_house_no' => 'House No',
                    'pin_no' => 'Pincode'
                ];
                $count = 0;
                foreach ($fields as $name => $label) {
                    if ($count % 2 == 0) {
                        echo '</div><div class="row">';
                    }
                    echo "<div class='col-md-6'><label>$label</label><input type='text' name='$name' class='form-control' value='" . htmlspecialchars($user[$name]) . "' disabled></div>";
                    $count++;
                }
                ?>
            </div>
            <div class="d-grid gap-2">
                <button type="button" id="editBtn" class="btn btn-primary">Edit Profile</button>
                <button type="submit" id="saveBtn" class="btn btn-success" style="display:none;">Save Changes</button>
            </div>
        </form>
    </div>
    <?php include 'footer.php';?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Enable editing
        document.getElementById('editBtn').onclick = function() {
            document.querySelectorAll('.profile-settings-form input[type="text"]').forEach(input => input.disabled = false);
            this.style.display = 'none';
            document.getElementById('saveBtn').style.display = 'block';
        }

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

        // AJAX form submission
        $('#profileForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            let formData = new FormData(this); // Create FormData object
            formData.append('u_img', $('#u_img')[0].files[0]); // Append the profile picture file

            $.ajax({
                url: 'update_profile.php', // PHP file to handle the update
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let result = JSON.parse(response);
                    if (result.status === 'success') {
                        $('#profile-settings-msg').text(result.message).css('color', 'green');

                        // Update the profile picture if changed
                        if (result.profile_pic) {
                            $('#profilePreview').attr('src', result.profile_pic);
                        }

                        // Disable inputs after saving
                        document.querySelectorAll('.profile-settings-form input[type="text"]').forEach(input => input.disabled = true);
                        $('#editBtn').show();
                        $('#saveBtn').hide();
                    } else {
                        $('#profile-settings-msg').text(result.message).css('color', 'red');
                    }
                },
                error: function(xhr, status, error) {
                    $('#profile-settings-msg').text('An error occurred. Please try again.').css('color', 'red');
                }
            });
        });
    </script>
</body>
</html>