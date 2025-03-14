<?php
include 'navbar.php';
include 'connect.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo '<script>window.location.href = "login.php";</script>';
    exit();
}

$u_id = $_SESSION['uid']; // Logged-in user ID

// Fetch treatments
$treatmentQuery = "SELECT * FROM doctors";
$treatmentResult = mysqli_query($con, $treatmentQuery);

// Fetch appointment history for the logged-in user
$appointmentQuery = "
    SELECT 
        appointments.*, 
        doctors.d_name AS doctor_name 
    FROM 
        appointments 
    LEFT JOIN 
        doctors 
    ON 
        appointments.d_id = doctors.d_id
";
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
        :root {
            --primary-color: #26988c;
            --primary-hover: #1f7a6f;
            --background-color: #f8f9fa;
            --text-color: #333;
            --white: #ffffff;
        }

        .appointment-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .appointment-form-container {
            background: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .appointment-form-container h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
        }

        .appointment-form-label {
            font-weight: 500;
            color: var(--text-color);
        }

        .appointment-form-select, .appointment-form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
        }

        .appointment-form-select:focus, .appointment-form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(38, 152, 140, 0.5);
        }

        .appointment-btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            color: var(--white);
            transition: background-color 0.3s ease;
        }

        .appointment-btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .appointment-btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            color: var(--white);
            transition: background-color 0.3s ease;
        }

        .appointment-btn-secondary:hover {
            background-color: #5a6268;
        }

        .appointment-table {
            width: 100%;
            border-collapse: collapse;
        }

        .appointment-table th, .appointment-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .appointment-table th {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .appointment-table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(38, 152, 140, 0.05);
        }

        @media (max-width: 768px) {
            .appointment-container {
                padding: 10px;
            }

            .appointment-form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="appointment-container">
        <!-- Appointment Booking Form -->
        <div class="appointment-form-container">
            <h2>Book an Appointment</h2>
            <form action="book_appointment.php" method="POST">
                <div class="mb-3">
                    <label for="treatment" class="appointment-form-label">Select Treatment</label>
                    <select class="appointment-form-select" id="specialization" name="specialization" required>
                        <option value="">Choose a specialization</option>
                        <?php
                        // Fetch all unique specializations from the doctors table
                        $specializationQuery = "SELECT DISTINCT specialization FROM doctors";
                        $specializationResult = mysqli_query($con, $specializationQuery);

                        while ($specialization = mysqli_fetch_assoc($specializationResult)) { ?>
                            <option value="<?php echo $specialization['specialization']; ?>">
                                <?php echo $specialization['specialization']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="doctor" class="appointment-form-label">Select Doctor</label>
                    <select class="appointment-form-select" id="doctor" name="d_id" required>
                        <option value="">Choose a doctor</option>
                        <!-- Doctors will be dynamically populated using JavaScript -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date" class="appointment-form-label">Appointment Date</label>
                    <input type="date" class="appointment-form-control" id="date" name="appointment_date" required>
                </div>
                <div class="mb-3">
                    <label for="time" class="appointment-form-label">Appointment Time</label>
                    <select class="appointment-form-select" id="time" name="appointment_time" required>
                        <option value="">Select a time</option>
                        <?php
                        // Generate time slots from 9:00 AM to 6:00 PM with 20-minute intervals
                        $startTime = strtotime('09:00');
                        $endTime = strtotime('18:00');
                        $interval = 20 * 60; // 20 minutes in seconds

                        for ($time = $startTime; $time <= $endTime; $time += $interval) {
                            echo '<option value="' . date('H:i', $time) . '">' . date('h:i A', $time) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="appointment-btn-primary w-100">Book Appointment</button>
            </form>
        </div>

        <!-- Appointment History Button -->
        <button class="appointment-btn-secondary mt-3 w-100" data-bs-toggle="modal" data-bs-target="#historyModal">
            View Appointment History
        </button>
    </div>

    <!-- Appointment History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Appointment History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="appointment-table appointment-table-striped">
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
                                    <td><?php echo $appointment['specialization']; ?></td>
                                    <td><?php echo $appointment['appointment_date']; ?></td>
                                    <td><?php echo $appointment['appointment_time']; ?></td>
                                    <td><?php echo $appointment['status']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="appointment-btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'footer.php';
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        document.getElementById('specialization').addEventListener('change', function () {
            const specialization = this.value;
            const doctorSelect = document.getElementById('doctor');

            doctorSelect.innerHTML = '<option value="">Choose a doctor</option>';

            if (specialization) {
                fetch(`fetch_doctors.php?specialization=${specialization}`)
                    .then(response => response.json())
                    .then(doctors => {
                        doctors.forEach(doctor => {
                            const option = document.createElement('option');
                            option.value = doctor.d_id; // Use doctor ID for booking
                            option.textContent = doctor.d_name; // Show doctor name
                            doctorSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching doctors:', error));
            }
        });
    </script>
</body>
</html>