<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['DOCTOR_LOGIN'])) {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['DOCTOR_ID'];

// Fetch reviews for the logged-in doctor
$sql = "SELECT dr.*, u.u_name 
        FROM doctor_reviews dr 
        JOIN users u ON dr.user_id = u.u_id 
        WHERE dr.doctor_id = $doctor_id 
        ORDER BY dr.added_on DESC";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .doctor-review-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .doctor-review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .doctor-review-user {
            font-size: 18px;
            font-weight: 600;
            color: #26988c;
        }
        .doctor-review-text {
            font-size: 16px;
            color: #666;
        }
        .doctor-review-date {
            font-size: 14px;
            color: #999;
        }
    </style>
</head>
<body>
<?php include 'nav.php';?>
    <div class="container py-5">
        <h2 class="text-center mb-4" style="color: #26988c;">Your Reviews</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="row">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-md-6">
                        <div class="doctor-review-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="doctor-review-user"><?php echo htmlspecialchars($row['u_name']); ?></h5>
                                <p class="doctor-review-date"><?php echo date("F j, Y, g:i a", strtotime($row['added_on'])); ?></p>
                            </div>
                            <p class="doctor-review-text"><?php echo htmlspecialchars($row['review_text']); ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No reviews found.
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>