<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['DOCTOR_LOGIN'])) {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['DOCTOR_ID'];
?>


    <title>Doctor Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
       
        .doctor-welcome {
            font-size: 28px;
            font-weight: 600;
            color: #26988c;
            margin-bottom: 30px;
            text-align: center;
        }
        .doctor-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
        }
        .doctor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }
        .doctor-card-body {
            padding: 30px;
            text-align: center;
        }
        .doctor-card-title {
            font-size: 24px;
            font-weight: 600;
            color: #26988c;
            margin-bottom: 15px;
        }
        .doctor-card-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
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
    </style>
<?php include 'nav.php';?>
    <!-- Main Content -->
    <div class="container py-5">
        <h2 class="doctor-welcome">Welcome, <?php echo $_SESSION['DOCTOR_NAME']; ?></h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Profile Card -->
            <div class="col">
                <div class="doctor-card h-100">
                    <div class="doctor-card-body">
                        <h5 class="doctor-card-title">Profile</h5>
                        <p class="doctor-card-text">View and update your profile details.</p>
                        <a href="profile.php" class="btn btn-primary">Go to Profile</a>
                    </div>
                </div>
            </div>

            <!-- Appointments Card -->
            <div class="col">
                <div class="doctor-card h-100">
                    <div class="doctor-card-body">
                        <h5 class="doctor-card-title">Appointments</h5>
                        <p class="doctor-card-text">View your booked appointments.</p>
                        <a href="appointments.php" class="btn btn-primary">View Appointments</a>
                    </div>
                </div>
            </div>

            <!-- Reviews Card -->
            <div class="col">
                <div class="doctor-card h-100">
                    <div class="doctor-card-body">
                        <h5 class="doctor-card-title">Reviews</h5>
                        <p class="doctor-card-text">See what patients are saying about you.</p>
                        <a href="reviews.php" class="btn btn-primary">View Reviews</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>