<?php
include "navbar.php";
include "connect.php";

$doctorId = $_GET['id'];

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
  echo '<script>window.location.href = "login.php";</script>';
  exit();
}

// Fetch doctor details
$doctorQuery = "SELECT * FROM doctors WHERE d_id = $doctorId";
$doctorResult = mysqli_query($con, $doctorQuery);
$doctor = mysqli_fetch_assoc($doctorResult);

// Fetch reviews for this doctor with user details
$reviewsQuery = "SELECT dr.*, u.u_name, u.u_img 
                 FROM doctor_reviews dr 
                 JOIN users u ON dr.user_id = u.u_id 
                 WHERE dr.doctor_id = $doctorId 
                 ORDER BY dr.added_on DESC";
$reviewsResult = mysqli_query($con, $reviewsQuery);

// Set $userId if the user is logged in
$userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
?>

<section class="section doctor-details">
  <div class="container">
    <div class="row">
      <!-- Doctor Details Card (Left Side) -->
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
            <img src="doctor\doctor_img/<?php echo htmlspecialchars($doctor['image']); ?>" alt="Doctor Image" class="img-fluid rounded-circle mb-3" style="width: 200px; height: 200px; object-fit: cover;">
            <h2 class="card-title"><?php echo htmlspecialchars($doctor['d_name']); ?></h2>
            <p class="text-muted"><?php echo htmlspecialchars($doctor['specialization']); ?></p>
            <hr>
            <p><strong>Experience:</strong> <?php echo htmlspecialchars($doctor['experience']); ?> years</p>
            <p><strong>Consultation Fee:</strong>₹<?php echo htmlspecialchars($doctor['consultation_fee']); ?></p>
            <p><strong>Available Days:</strong> <?php echo htmlspecialchars($doctor['available_days']); ?></p>
            <p><strong>Available Time:</strong> <?php echo htmlspecialchars($doctor['available_time']); ?></p>
            <hr>
            <!-- Social Media Links -->
            <div class="social-media-links">
              <?php if (!empty($doctor['facebook'])): ?>
                <a href="<?php echo htmlspecialchars($doctor['facebook']); ?>" target="_blank" class="btn btn-outline-primary btn-sm mb-2"><i class="fab fa-facebook"></i> Facebook</a>
              <?php endif; ?>
              <?php if (!empty($doctor['twitter'])): ?>
                <a href="<?php echo htmlspecialchars($doctor['twitter']); ?>" target="_blank" class="btn btn-outline-info btn-sm mb-2"><i class="fab fa-twitter"></i> Twitter</a>
              <?php endif; ?>
              <?php if (!empty($doctor['linkedin'])): ?>
                <a href="<?php echo htmlspecialchars($doctor['linkedin']); ?>" target="_blank" class="btn btn-outline-primary btn-sm mb-2"><i class="fab fa-linkedin"></i> LinkedIn</a>
              <?php endif; ?>
              <?php if (!empty($doctor['instagram'])): ?>
                <a href="<?php echo htmlspecialchars($doctor['instagram']); ?>" target="_blank" class="btn btn-outline-danger btn-sm mb-2"><i class="fab fa-instagram"></i> Instagram</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Doctor Information and Reviews Section (Right Side) -->
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <!-- About the Doctor -->
            <h4 class="card-title">About the Doctor</h4>
            <p class="card-text"><?php echo htmlspecialchars($doctor['about']); ?></p>

            <hr>

            <!-- Professional Information -->
            <h4 class="card-title">Professional Information</h4>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>Qualifications:</strong> <?php echo htmlspecialchars($doctor['qualifications']); ?></li>
              <li class="list-group-item"><strong>Languages Spoken:</strong> <?php echo htmlspecialchars($doctor['languages']); ?></li>
              <li class="list-group-item"><strong>Awards:</strong> <?php echo htmlspecialchars($doctor['awards']); ?></li>
            </ul>

            <hr>

            <!-- Contact Information -->
            <h4 class="card-title">Contact Information</h4>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($doctor['email']); ?></li>
              <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['phone']); ?></li>
            </ul>

            <hr>

            <!-- Patient Reviews Carousel -->
            <h4 class="card-title">Patient Reviews</h4>
            <div id="reviewCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
              <div class="carousel-inner">
                <?php
                if (mysqli_num_rows($reviewsResult) > 0) {
                  $isFirst = true;
                  while ($review = mysqli_fetch_assoc($reviewsResult)) {
                    echo '<div class="carousel-item' . ($isFirst ? ' active' : '') . '">';
                    echo '<div class="review text-center">';
                    // echo '<img src="images/profilepic/' . htmlspecialchars($review['u_img']) . '" alt="User Image" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">';
                    echo '<p><strong>' . htmlspecialchars($review['u_name']) . ':</strong> ' . htmlspecialchars($review['review_text']) . '</p>';
                    echo '<p class="text-muted"><small>' . date("F j, Y, g:i a", strtotime($review['added_on'])) . '</small></p>';
                    echo '</div></div>';
                    $isFirst = false;
                  }
                } else {
                  echo '<div class="carousel-item active">';
                  echo '<div class="review text-center">';
                  echo '<p>No reviews yet. Be the first to review!</p>';
                  echo '</div></div>';
                }
                ?>
              </div>
              <!-- Carousel Controls -->
              <button class="carousel-control-prev" type="button" data-bs-target="#reviewCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#reviewCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>

            <?php if ($userId): ?>
              <hr>
              <h4 class="card-title">Write a Review</h4>
              <form id="doctorReviewForm">
                <div class="mb-3">
                  <textarea class="form-control" id="doctorReviewText" name="reviewText" rows="3" placeholder="Write your review..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
              </form>
            <?php else: ?>
              <p class="text-danger">You must <a href="login.php">log in</a> to leave a review.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Handle review form submission
    $("#doctorReviewForm").submit(function(e) {
        e.preventDefault();
        var reviewText = $("#doctorReviewText").val();
        $.ajax({
            url: "submit_doctor_review.php",
            type: "POST",
            data: { doctor_id: <?php echo $doctorId; ?>, user_id: <?php echo $userId; ?>, review_text: reviewText },
            success: function(response) {
                if (response === "success") {
                    alert("Review submitted successfully!");
                    location.reload();
                } else {
                    alert("Error submitting review!");
                }
            }
        });
    });
});
</script>

<!-- Font Awesome for Social Media Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php include "footer.php"; ?>
<style>
/* Doctor Details Card */
.card {
  border: none;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

.card-body {
  padding: 20px;
}

.card-title {
  color: #26988c;
  margin-bottom: 20px;
}

/* Social Media Links */
.social-media-links .btn {
  width: 100%;
  text-align: left;
}

.social-media-links .btn i {
  margin-right: 10px;
}

/* Review Carousel */
#reviewCarousel {
  background-color: #f8f9fa;
  border-radius: 10px;
  padding: 20px;
  margin-bottom: 20px;
}

#reviewCarousel .carousel-item {
  text-align: center;
}

#reviewCarousel .review p {
  margin-bottom: 5px;
}

#reviewCarousel .review small {
  color: #666;
}

/* Carousel Controls */
.carousel-control-prev,
.carousel-control-next {
  width: 5%;
  color: #26988c;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: #26988c;
  border-radius: 50%;
  padding: 10px;
}
</style>