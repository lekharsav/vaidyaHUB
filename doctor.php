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

    <!-- Search and Filter Section -->
    <div class="row justify-content-center mb-5">
      <div class="col-md-6 text-center">
        <!-- Search Bar -->
        <input type="text" id="search-bar" class="form-control mb-3" placeholder="Search by doctor name...">
        <!-- Specialization Filter Dropdown -->
        <select id="specialization-filter" class="form-control">
          <option value="all">All Specializations</option>
          <?php
          // Fetch all unique specializations
          $specializationQuery = "SELECT DISTINCT specialization FROM doctors WHERE status = 1";
          $specializationResult = mysqli_query($con, $specializationQuery);

          while ($specialization = mysqli_fetch_assoc($specializationResult)) {
            $catValue = strtolower(str_replace(' ', '', $specialization['specialization'])); // Remove spaces and convert to lowercase
            echo '<option value="' . $catValue . '">' . $specialization['specialization'] . '</option>';
          }
          ?>
        </select>
      </div>
    </div>

    <!-- Doctor Cards -->
    <div class="row shuffle-wrapper portfolio-gallery">
      <?php
      while ($doctor = mysqli_fetch_assoc($doctorResult)) {
        $catValue = strtolower(str_replace(' ', '', $doctor['specialization'])); // Remove spaces and convert to lowercase
        echo '<div class="col-lg-3 col-sm-6 col-md-6 mb-4 shuffle-item" data-groups="[\'' . $catValue . '\']" data-name="' . strtolower($doctor['d_name']) . '">
                <div class="position-relative doctor-inner-box" onclick="window.location.href=\'doctor-single.php?id=' . $doctor['d_id'] . '\'">
                    <div class="doctor-profile">
                        <div class="doctor-img">
                            <img src="doctors/' . htmlspecialchars($doctor["image"]) . '" alt="Doctor Image" class="img-fluid">
                        </div>
                    </div>
                    <div class="content mt-3">
                        <h4 class="mb-0">' . htmlspecialchars($doctor['d_name']) . '</h4>
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

<!-- Footer -->
<?php
include "footer.php";
?>

<!-- Add this script at the end of your doctor.php file, just before the closing </body> tag -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchBar = document.getElementById('search-bar');
  const specializationFilter = document.getElementById('specialization-filter');
  const doctorCards = document.querySelectorAll('.shuffle-item');

  // Function to filter doctors
  function filterDoctors() {
    const searchTerm = searchBar.value.toLowerCase();
    const selectedSpecialization = specializationFilter.value.toLowerCase();

    doctorCards.forEach(card => {
      const cardName = card.getAttribute('data-name').toLowerCase();
      const cardSpecialization = card.getAttribute('data-groups').toLowerCase();

      // Show or hide the card based on search term and specialization
      if ((selectedSpecialization === 'all' || cardSpecialization.includes(selectedSpecialization)) &&
          (cardName.includes(searchTerm))) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  // Add event listeners
  searchBar.addEventListener('input', filterDoctors);
  specializationFilter.addEventListener('change', filterDoctors);
});
</script>

<!-- Add this CSS for the search bar, filter dropdown, and doctor cards -->
<style>
/* Search Bar and Filter Dropdown */
#search-bar, #specialization-filter {
  border: 1px solid #26988c;
  border-radius: 5px;
  padding: 10px;
  font-size: 16px;
  width: 100%;
  max-width: 400px;
  margin: 0 auto 20px;
}

#search-bar:focus, #specialization-filter:focus {
  outline: none;
  border-color: #1f7a6f;
}

/* Doctor Cards */
.shuffle-item {
  display: block;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.doctor-inner-box {
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  overflow: hidden;
  transition: all 0.3s ease;
  cursor: pointer;
}

.doctor-inner-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  border-color: #26988c;
}

.doctor-img img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.content {
  padding: 15px;
  text-align: center;
}

.content h4 {
  font-size: 18px;
  margin-bottom: 5px;
  color: #333;
}

.content p {
  font-size: 14px;
  color: #666;
}
</style>