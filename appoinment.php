<?php

include 'navbar.php';
include 'connect.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$u_id =  $_SESSION['uid']; // Logged-in user ID

// Fetch treatments
$treatmentQuery = "SELECT * FROM doctors";
$treatmentResult = mysqli_query($con, $treatmentQuery);

// Fetch appointment history for the logged-in user
$appointmentQuery = "select * from appointments";
$appointmentResult = mysqli_query($con, $appointmentQuery);
?>




    <div class="container" style="margin-top: 30px;">
        <!-- Appointment Booking Form -->
        <div class="form-container">
            <h2 class="text-center mb-4">Book an Appointment</h2>
            <form action="book_appointment.php" method="POST">
                <div class="mb-3">
                    <label for="treatment" class="form-label">Select Treatment</label>
                    <select class="form-select" id="specialization" name="specialization" required>
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
<style>


     
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
