<?php
include "navbar.php";
include "connect.php";
?>

<!-- Lab Test Section -->
<div class="container mt-5">
  <h2 class="text-center mb-4">📑 Book a Lab Test</h2>

  <div class="row">
    <?php
    $query = "SELECT * FROM lab_tests WHERE status = 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo '
        <div class="col-md-4" >
            <div class="card mb-4 shadow-lg lab-card" >
                <img src="images/service/' . $row['image'] . '" class="card-img-top img-fluid" alt="' . $row['test_name'] . ' style =object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">' . $row['test_name'] . '</h5>
                    <p class="card-text text-muted">' . $row['description'] . '</p>
                    <p class="card-text"><strong> Price:</strong> ₹' . $row['price'] . '</p>
                    <button class="btn book-btn" data-bs-toggle="modal" data-bs-target="#bookTestModal" 
                        data-id="' . $row['lt_id'] . '" 
                        data-name="' . $row['test_name'] . '">
                        Book Now
                    </button>
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
  <div class="modal-dialog modal-lg modal-animate"> <!-- Smooth transition effect -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">🧪 Book Your Lab Test</h5>
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
              <label for="patient_name" class="form-label">Patient Name</label>
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
    <input list="states" class="form-control" name="state" placeholder="Type to search..." required>
    <datalist id="states">
        <option value="Andhra Pradesh">
        <option value="Arunachal Pradesh">
        <option value="Assam">
        <option value="Bihar">
        <option value="Chhattisgarh">
        <option value="Goa">
        <option value="Gujarat">
        <option value="Haryana">
        <option value="Himachal Pradesh">
        <option value="Jharkhand">
        <option value="Karnataka">
        <option value="Kerala">
        <option value="Madhya Pradesh">
        <option value="Maharashtra">
        <option value="Manipur">
        <option value="Meghalaya">
        <option value="Mizoram">
        <option value="Nagaland">
        <option value="Odisha">
        <option value="Punjab">
        <option value="Rajasthan">
        <option value="Sikkim">
        <option value="Tamil Nadu">
        <option value="Telangana">
        <option value="Tripura">
        <option value="Uttar Pradesh">
        <option value="Uttarakhand">
        <option value="West Bengal">
        <option value="Andaman and Nicobar Islands">
        <option value="Chandigarh">
        <option value="Dadra and Nagar Haveli and Daman and Diu">
        <option value="Lakshadweep">
        <option value="Delhi">
        <option value="Puducherry">
        <option value="Ladakh">
        <option value="Jammu and Kashmir">
    </datalist>
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
            <button type="submit" class="btn btn-success book-confirm">✅ Confirm Booking</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include "footer.php"?>
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
  /* Smooth hover effect on lab test cards */
  .lab-card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .lab-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Image Container */
    .card-img-top {
        width: 100%;
        height: 200px; /* Fixed height for all images */
        object-fit: cover; /* Ensures the image covers the area without stretching */
        object-position: center; /* Centers the image within the container */
    }

    /* Card Body */
    .card-body {
        padding: 20px;
    }

    .card-title {
        font-size: 1.25rem;
        margin-bottom: 10px;
    }

    .card-text {
        font-size: 0.9rem;
        color: #666;
    }

    /* Book Now Button */
    .book-btn {
        background-color: #26988c;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .book-btn:hover {
        background-color: #1f7a6e;
    }

  /* Smooth modal appearance */
  .modal-animate .modal-content {
    animation: fadeInUp 0.5s ease-in-out;
  }

  @keyframes fadeInUp {
    from {
      transform: translateY(50px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  /* Confirm Booking Button */
  .book-confirm {
    background-color: #28a745;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    transition: all 0.3s ease;
  }

  .book-confirm:hover {
    background-color: #218838;
    transform: scale(1.1);
  }
</style>
