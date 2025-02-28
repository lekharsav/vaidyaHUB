<?php
session_start();
include 'connect.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$u_id =  $_SESSION['uid'] ;// Logged-in user ID

// Fetch treatments
$treatmentQuery = "SELECT * FROM treatments";
$treatmentResult = mysqli_query($con, $treatmentQuery);

// Fetch appointment history for the logged-in user
$appointmentQuery = "SELECT a.*, u.u_name AS doctor_name, t.treatment_name 
                     FROM appointments a
                     JOIN users u ON a.d_id = u.u_id
                     JOIN treatments t ON a.treatment_id = t.treatment_id
                     WHERE a.p_id = $u_id
                     ORDER BY a.appointment_date DESC";
$appointmentResult = mysqli_query($con, $appointmentQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .history-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100%;
            background: white;
            box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease;
            padding: 20px;
            z-index: 1000;
        }
        .history-sidebar.open {
            right: 0;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        .overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Appointment Booking Form -->
        <div class="form-container">
            <h2 class="text-center mb-4">Book an Appointment</h2>
            <form action="book_appointment.php" method="POST">
                <div class="mb-3">
                    <label for="treatment" class="form-label">Select Treatment</label>
                    <select class="form-select" id="treatment" name="treatment_id" required>
                        <option value="">Choose a treatment</option>
                        <?php while ($treatment = mysqli_fetch_assoc($treatmentResult)) { ?>
                            <option value="<?php echo $treatment['treatment_id']; ?>">
                                <?php echo $treatment['treatment_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="doctor" class="form-label">Select Doctor</label>
                    <select class="form-select" id="doctor" name="d_id" required>
                        <option value="">Choose a doctor</option>
                        <!-- Doctors will be dynamically populated using JavaScript -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Appointment Date</label>
                    <input type="date" class="form-control" id="date" name="appointment_date" required>
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Appointment Time</label>
                    <input type="time" class="form-control" id="time" name="appointment_time" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
            </form>
        </div>

        <!-- Appointment History Button -->
        <button class="btn btn-secondary mt-3 w-100" onclick="toggleHistorySidebar()">
            View Appointment History
        </button>
    </div>

    <!-- Appointment History Sidebar -->
    <div class="history-sidebar" id="historySidebar">
        <h3>Appointment History</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Treatment</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($appointment = mysqli_fetch_assoc($appointmentResult)) { ?>
                    <tr>
                        <td><?php echo $appointment['doctor_name']; ?></td>
                        <td><?php echo $appointment['treatment_name']; ?></td>
                        <td><?php echo $appointment['appointment_date']; ?></td>
                        <td><?php echo $appointment['appointment_time']; ?></td>
                        <td><?php echo $appointment['status']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="toggleHistorySidebar()"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Fetch doctors based on selected treatment
        document.getElementById('treatment').addEventListener('change', function () {
            const treatmentId = this.value;
            const doctorSelect = document.getElementById('doctor');
            doctorSelect.innerHTML = '<option value="">Choose a doctor</option>';

            if (treatmentId) {
                fetch(`fetch_doctors.php?treatment_id=${treatmentId}`)
                    .then(response => response.json())
                    .then(doctors => {
                        doctors.forEach(doctor => {
                            const option = document.createElement('option');
                            option.value = doctor.u_id;
                            option.textContent = doctor.u_name;
                            doctorSelect.appendChild(option);
                        });
                    });
            }
        });

        // Toggle history sidebar
        function toggleHistorySidebar() {
            const sidebar = document.getElementById('historySidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
    </script>
</body>
</html>