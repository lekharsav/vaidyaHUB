<?php
include "navbar.php";
include "connect.php";

// Fetch all active doctors from the database
$doctorQuery = "SELECT * FROM doctors WHERE status = 1";
$doctorResult = mysqli_query($con, $doctorQuery);
?>

<!-- portfolio -->
<section class="section doctors">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 text-center">
        <div class="section-title">
          <h2>Doctors</h2>
          <div class="divider mx-auto my-4"></div>
          <p>We provide a wide range of creative services adipisicing elit. Autem maxime rem modi eaque, voluptate. Beatae officiis neque </p>
        </div>
      </div>
    </div>

    <!-- Specialization Filter Buttons -->
    <div class="col-12 text-center mb-5">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn active">
          <input type="radio" name="shuffle-filter" value="all" checked="checked" />All Department
        </label>
        <?php
        // Fetch all unique specializations
        $specializationQuery = "SELECT DISTINCT specialization FROM doctors WHERE status = 1";
        $specializationResult = mysqli_query($con, $specializationQuery);

        while ($specialization = mysqli_fetch_assoc($specializationResult)) {
          $catValue = strtolower(str_replace(' ', '', $specialization['specialization']));
          echo '<label class="btn">
                  <input type="radio" name="shuffle-filter" value="' . $catValue . '" />' . $specialization['specialization'] . '
                </label>';
        }
        ?>
      </div>
    </div>

    <!-- Doctor Cards -->
    <div class="row shuffle-wrapper portfolio-gallery">
      <?php
      while ($doctor = mysqli_fetch_assoc($doctorResult)) {
        $catValue = strtolower(str_replace(' ', '', $doctor['specialization']));
        echo '<div class="col-lg-3 col-sm-6 col-md-6 mb-4 shuffle-item" data-groups="[\''
    . $catValue . '\']">
        <div class="position-relative doctor-inner-box">
            <div class="doctor-profile">
                <div class="doctor-img">
                    <img src="doctors/' . htmlspecialchars($doctor["image"]) . '" alt="Medicine Image" style="height: 300px;width: 201px;object-fit: cover;">
                </div>
            </div>
            <div class="content mt-3">
                <h4 class="mb-0"><a href="doctor-single.php">' . htmlspecialchars($doctor['d_name']) . '</a></h4>
                <p>' . htmlspecialchars($doctor['specialization']) . '</p>
            </div>
        </div>
    </div>';

      }
      ?>
    </div>
  </div>
</section>
<!-- /portfolio -->

<!-- Call to Action Section -->

<!-- Footer -->
<?php
include "footer.php";
?>