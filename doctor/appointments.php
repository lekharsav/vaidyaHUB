<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['DOCTOR_LOGIN'])) {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['DOCTOR_ID'];

// Fetch appointments for the logged-in doctor
$sql = "SELECT a.*, u.u_name 
        FROM appointments a 
        JOIN users u ON a.p_id = u.u_id 
        WHERE a.d_id = $doctor_id 
        ORDER BY a.appointment_date DESC";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .doctor-appointment-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .doctor-appointment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .doctor-status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .doctor-status-pending {
            background: #ffc107;
            color: #000;
        }
        .doctor-status-confirmed {
            background: #26988c;
            color: #fff;
        }
        .doctor-status-completed {
            background: #17a2b8;
            color: #fff;
        }
        .doctor-status-cancelled {
            background: #982634;
            color: #fff;
        }
        .doctor-theme-color {
            color: #26988c;
        }
    </style>
</head>
<body>
<?php include 'nav.php';?>
    <div class="container py-5">
        <h2 class="text-center mb-4 doctor-theme-color">Your Appointments</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="row">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-md-6">
                        <div class="doctor-appointment-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0"><?php echo htmlspecialchars($row['u_name']); ?></h5>
                                <span class="doctor-status-badge 
                                    <?php 
                                    if ($row['status'] == 'Pending') echo 'doctor-status-pending';
                                    elseif ($row['status'] == 'Confirmed') echo 'doctor-status-confirmed';
                                    elseif ($row['status'] == 'Completed') echo 'doctor-status-completed';
                                    elseif ($row['status'] == 'Cancelled') echo 'doctor-status-cancelled';
                                    ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </div>
                            <p class="mb-2"><strong>Date:</strong> <?php echo htmlspecialchars($row['appointment_date']); ?></p>
                            <p class="mb-2"><strong>Time:</strong> <?php echo htmlspecialchars($row['appointment_time']); ?></p>
                            <p class="mb-2"><strong>Specialization:</strong> <?php echo htmlspecialchars($row['specialization']); ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No appointments found.
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>