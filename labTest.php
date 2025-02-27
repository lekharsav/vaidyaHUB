<?php
include "navbar.php";
include "connect.php";
?>

<!-- Lab Test Section -->
<div class="container mt-5">
  <h2 class="text-center mb-4">Book a Lab Test</h2>

  <div class="row">
    <?php
    $query = "SELECT * FROM lab_tests WHERE status = 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo '
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="' . $row['image'] . '" class="card-img-top" alt="' . $row['test_name'] . '">
                        <div class="card-body">
                            <h5 class="card-title">' . $row['test_name'] . '</h5>
                            <p class="card-text">' . $row['description'] . '</p>
                            <p class="card-text"><strong>Price:</strong> ₹' . $row['price'] . '</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookTestModal" data-id="' . $row['lt_id'] . '" data-name="' . $row['test_name'] . '">Book Now</button>
                        </div>
                    </div>
                </div>';
      }
    } else {
      echo '<p class="text-center">No lab tests available at the moment.</p>';
    }
    ?>
  </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookTestModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Increased modal size -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Book Lab Test</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="book_labtest.php" method="POST">
          <input type="hidden" id="test_id" name="test_id">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="test_name" class="form-label">Test Name</label>
              <input type="text" class="form-control" id="test_name" name="test_name" readonly>
            </div>
            <div class="col-md-6 mb-3">
              <label for="patient_name" class="form-label">Your Name</label>
              <input type="text" class="form-control" name="patient_name" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="test_date" class="form-label">Select Date</label>
              <input type="date" class="form-control" name="test_date" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="state" class="form-label">State</label>
              <input type="text" class="form-control" name="state" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Email ID</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="phone_no" class="form-label">Phone No</label>
              <input type="text" class="form-control" name="phone_no" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control" name="address" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="flat_house_no" class="form-label">Flat/House No.</label>
              <input type="text" class="form-control" name="flat_house_no" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="pin_no" class="form-label">Pin Code</label>
              <input type="text" class="form-control" name="pin_no" required>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-success px-4">Confirm Booking</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Set modal values when booking button is clicked
  document.querySelectorAll('button[data-bs-target="#bookTestModal"]').forEach(button => {
    button.addEventListener('click', function() {
      document.getElementById('test_id').value = this.getAttribute('data-id');
      document.getElementById('test_name').value = this.getAttribute('data-name');
    });
  });
</script>

<style>
  .modal-lg {
    max-width: 700px;
  }

  .modal-title {
    font-weight: bold;
  }

  .btn-success {
    background-color: #28a745;
    border: none;
    transition: 0.3s;
  }

  .btn-success:hover {
    background-color: #218838;
  }

  .form-label {
    font-weight: bold;
  }
</style>
