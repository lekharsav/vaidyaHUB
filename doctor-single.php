<?php
include "navbar.php";
include "connect.php";

// Get the doctor ID from the URL
$doctorId = $_GET['id'];

// Fetch the doctor's details from the database
$doctorQuery = "SELECT * FROM doctors WHERE d_id = $doctorId";
$doctorResult = mysqli_query($con, $doctorQuery);
$doctor = mysqli_fetch_assoc($doctorResult);
?>

<!-- Doctor Details Section -->
<section class="section doctor-details">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        <div class="doctor-img">
          <img src="doctors/<?php echo htmlspecialchars($doctor['image']); ?>" alt="Doctor Image" class="img-fluid">
        </div>
      </div>
      <div class="col-lg-8">
        <h2><?php echo htmlspecialchars($doctor['d_name']); ?></h2>
        <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialization']); ?></p>
        <p><strong>Experience:</strong> <?php echo htmlspecialchars($doctor['experience']); ?> years</p>
        <p><strong>Consultation Fee:</strong> $<?php echo htmlspecialchars($doctor['consultation_fee']); ?></p>
        <p><strong>Available Days:</strong> <?php echo htmlspecialchars($doctor['available_days']); ?></p>
        <p><strong>Available Time:</strong> <?php echo htmlspecialchars($doctor['available_time']); ?></p>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<?php
include "footer.php";
?>