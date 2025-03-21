<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');
?>

<div class="main-content animated">
    <h1 class="mb-4">Dashboard</h1>
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white text-center p-4">
                <h5><i class="fas fa-users"></i> Total Users</h5>
                <p class="display-4"><?php echo $con->query("SELECT COUNT(*) FROM users")->fetch_row()[0]; ?></p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white text-center p-4">
                <h5><i class="fas fa-calendar-check"></i> Total Appointments</h5>
                <p class="display-4"><?php echo $con->query("SELECT COUNT(*) FROM appointments")->fetch_row()[0]; ?></p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white text-center p-4">
                <h5><i class="fas fa-user-md"></i> Total Doctors</h5>
                <p class="display-4"><?php echo $con->query("SELECT COUNT(*) FROM doctors")->fetch_row()[0]; ?></p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white text-center p-4">
                <h5><i class="fas fa-shopping-cart"></i> Total Orders</h5>
                <p class="display-4"><?php echo $con->query("SELECT COUNT(*) FROM orders")->fetch_row()[0]; ?></p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Appointment Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Doctor Specialization Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Fetch data for the pie chart (Appointment Status Distribution)
    <?php
    $appointmentStatus = $con->query("
        SELECT status, COUNT(*) as count 
        FROM appointments 
        GROUP BY status
    ")->fetch_all(MYSQLI_ASSOC);
    $statusLabels = json_encode(array_column($appointmentStatus, 'status'));
    $statusData = json_encode(array_column($appointmentStatus, 'count'));
    ?>

    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: <?php echo $statusLabels; ?>,
            datasets: [{
                label: 'Appointment Status',
                data: <?php echo $statusData; ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Appointment Status Distribution'
                }
            }
        }
    });

    // Fetch data for the bar chart (Doctor Specialization Distribution)
    <?php
    $doctorSpecialization = $con->query("
        SELECT specialization, COUNT(*) as count 
        FROM doctors 
        GROUP BY specialization
    ")->fetch_all(MYSQLI_ASSOC);
    $specializationLabels = json_encode(array_column($doctorSpecialization, 'specialization'));
    $specializationData = json_encode(array_column($doctorSpecialization, 'count'));
    ?>

    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: <?php echo $specializationLabels; ?>,
            datasets: [{
                label: 'Number of Doctors',
                data: <?php echo $specializationData; ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: 'Doctor Specialization Distribution'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include('includes/footer.php'); ?>