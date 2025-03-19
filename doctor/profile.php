<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['DOCTOR_LOGIN'])) {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['DOCTOR_ID'];

// Fetch doctor details
$sql = "SELECT * FROM doctors WHERE d_id = $doctor_id";
$result = mysqli_query($con, $sql);
$doctor = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $about = mysqli_real_escape_string($con, $_POST['about']);
    $languages = mysqli_real_escape_string($con, $_POST['languages']);
    $awards = mysqli_real_escape_string($con, $_POST['awards']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $facebook = mysqli_real_escape_string($con, $_POST['facebook']);
    $twitter = mysqli_real_escape_string($con, $_POST['twitter']);
    $linkedin = mysqli_real_escape_string($con, $_POST['linkedin']);
    $instagram = mysqli_real_escape_string($con, $_POST['instagram']);

    $update_sql = "UPDATE doctors SET 
                   about = '$about', 
                   languages = '$languages', 
                   awards = '$awards', 
                   phone = '$phone', 
                   facebook = '$facebook', 
                   twitter = '$twitter', 
                   linkedin = '$linkedin', 
                   instagram = '$instagram' 
                   WHERE d_id = $doctor_id";

    if (mysqli_query($con, $update_sql)) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating profile: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .profile-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .profile-left {
            flex: 1;
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-right {
            flex: 2;
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #26988c;
            margin-bottom: 20px;
        }
        .read-only {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        .form-control {
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .btn-primary {
            background: #26988c;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: #1f7a6f;
        }
        .btn-secondary {
            background: #982634;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        .btn-secondary:hover {
            background: #7a1d2a;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include 'nav.php';?>
    <div class="container py-5">
        <h2 class="text-center mb-4" style="color: #26988c;">Your Profile</h2>

        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php } elseif (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php } ?>

        <div class="profile-container">
            <!-- Left Column: Profile Photo and Read-Only Fields -->
            <div class="profile-left">
                <div class="text-center">
                    <img src="../doctor/doctor_img/<?php echo htmlspecialchars($doctor['image']); ?>" alt="Profile Image" class="profile-image">
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control read-only" id="name" value="<?php echo htmlspecialchars($doctor['d_name']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control read-only" id="email" value="<?php echo htmlspecialchars($doctor['email']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="specialization" class="form-label">Specialization</label>
                    <input type="text" class="form-control read-only" id="specialization" value="<?php echo htmlspecialchars($doctor['specialization']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="experience" class="form-label">Experience</label>
                    <input type="text" class="form-control read-only" id="experience" value="<?php echo htmlspecialchars($doctor['experience']); ?> years" readonly>
                </div>
                <div class="mb-3">
                    <label for="consultation_fee" class="form-label">Consultation Fee</label>
                    <input type="text" class="form-control read-only" id="consultation_fee" value="₹<?php echo htmlspecialchars($doctor['consultation_fee']); ?>" readonly>
                </div>
            </div>

            <!-- Right Column: Editable Fields -->
            <div class="profile-right">
                <form method="POST">
                    <div class="mb-3">
                        <label for="about" class="form-label">About Me</label>
                        <textarea class="form-control" id="about" name="about" rows="3"><?php echo htmlspecialchars($doctor['about']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="languages" class="form-label">Languages</label>
                        <input type="text" class="form-control" id="languages" name="languages" value="<?php echo htmlspecialchars($doctor['languages']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="awards" class="form-label">Awards</label>
                        <input type="text" class="form-control" id="awards" name="awards" value="<?php echo htmlspecialchars($doctor['awards']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($doctor['phone']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="facebook" class="form-label">Facebook</label>
                        <input type="text" class="form-control" id="facebook" name="facebook" value="<?php echo htmlspecialchars($doctor['facebook']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="twitter" class="form-label">Twitter</label>
                        <input type="text" class="form-control" id="twitter" name="twitter" value="<?php echo htmlspecialchars($doctor['twitter']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="linkedin" class="form-label">LinkedIn</label>
                        <input type="text" class="form-control" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($doctor['linkedin']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="instagram" class="form-label">Instagram</label>
                        <input type="text" class="form-control" id="instagram" name="instagram" value="<?php echo htmlspecialchars($doctor['instagram']); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>