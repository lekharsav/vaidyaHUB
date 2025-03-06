<?php
include('connect.php');
session_start();

if (isset($_SESSION['uid']) && $_SESSION['uid'] != null) {
    $u_id = $_SESSION['uid']; // Get the logged-in user's ID

    // Corrected SQL query using placeholder
    $sql = "SELECT * FROM users WHERE u_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $u_id); // Correctly bind integer parameter
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $profile_pic = !empty($user['u_img']) ? '' . $user['u_img'] : 'images/profilepic/pic.jpg';
    } else {
        $profile_pic = 'images/profilepic/pic.jpg'; // Default image if user not found
    }
} else {
    $profile_pic = 'images/profilepic/pic.jpg'; // Default for non-logged-in users
}
?>


<!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<!-- FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<!-- Icon Font CSS -->
<link rel="stylesheet" href="plugins/icofont/icofont.min.css">

<!-- Slick Slider CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

<!-- Owl Carousel CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Main Stylesheet -->
<link rel="stylesheet" href="css/style.css">

<!-- jQuery (Required for Owl Carousel & Swiper) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


  <header>
    <!-- Top Bar -->
   
    <!-- Fixed Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light   fixed-top py-0" style="background-color:#8bc0e3d9;">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="images/loge.png" alt="Logo" class="img-fluid" style="width: 140px; height: 50px;">
            </a>

            <!-- Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarmain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarmain">
                <ul class="navbar-nav mx-auto fw-bold">
                    <li class="nav-item"><a class="nav-link text-dark" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="order_details.php">Order</a></li>


                    <!-- Doctor Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-dark" href="#" data-bs-toggle="dropdown">Doctors</a>
                        <ul class="dropdown-menu border-0 shadow">
                            <li><a class="dropdown-item" href="doctor.php">Our Doctors</a></li>
                            <li><a class="dropdown-item" href="doctor-single.php">Doctor Profile</a></li>
                            <li><a class="dropdown-item" href="appointment.php">Book Appointment</a></li>
                        </ul>
                    </li>


                <!-- Right Side Buttons -->
                <ul class="navbar-nav d-flex align-items-center">
                    <!-- Cart Button -->
                    <li class="nav-item me-3">
                        <a href="cart.php" class="nav-link text-dark position-relative" style="margin-bottom: 15px;"> 
                            <i class="icofont-cart fs-4"></i>
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="cart-count">3</span>
                        </a>
                    </li>

                    <!-- Profile Button with Pop-out Menu -->
                    



                </ul>
            </div>


            <div class="nav-item dropdown" style="padding-left: 20px;">
    <a class="nav-link text-dark d-flex align-items-center dropdown-toggle" href="#" id="profileMenu" data-bs-toggle="dropdown">
        <?php if (isset($_SESSION['USER_LOGIN']) && $_SESSION['USER_LOGIN'] === 'yes') { ?>
            <img src="<?php echo $profile_pic; ?>" alt="User" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
        <?php } else { ?>
            <i class="icofont-user-alt-5 fs-4 me-2"></i>
        <?php } ?>
    </a>
    <ul class="dropdown-menu border-0 shadow p-3 dropdown-menu-start" aria-labelledby="profileMenu">
        <?php if (isset($_SESSION['USER_LOGIN']) && $_SESSION['USER_LOGIN'] === 'yes') { ?>
            <li><a class="dropdown-item" href="profile.php">My Account </a></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
        <?php } else { ?>
            <li><a class="dropdown-item" href="login.php">Login</a></li>
        <?php } ?>
    </ul>
</div>
        </div>
    </nav>
</header>

<!-- Add margin-top to prevent content from hiding behind the fixed navbar -->
<style>
    body {
        padding-top: 90px; /* Adjusted for fixed navbar */
    }

    .navbar {
        transition: all 0.3s ease-in-out;
    }

    .navbar .nav-item .nav-link {
        transition: color 0.3s ease-in-out;
    }

    .navbar .nav-item .nav-link:hover {
        color: #007bff !important;
    }

    /* Profile Dropdown Styling */
    /* Dropdown Menu Styling */
    .dropdown-menu {
        min-width: 200px; /* Set a minimum width for the dropdown */
        border-radius: 10px; /* Rounded corners */
        background-color: #fff; /* White background */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Soft shadow */
        border: none; /* Remove default border */
        padding: 10px 0; /* Add padding */
    }

    .dropdown-item {
        padding: 8px 16px; /* Add padding to dropdown items */
        font-size: 14px; /* Set font size */
        color: #333; /* Default text color */
        border-radius: 8px; /* Rounded corners for items */
        transition: all 0.3s ease; /* Smooth transition */
        display: flex; /* Align items */
        align-items: center; /* Center items vertically */
    }

    .dropdown-item:hover {
        background-color: #f8f9fa; /* Light background on hover */
        color: #26988c; /* Change text color on hover */
        transform: translateX(5px); /* Slight move to the right */
    }

    .dropdown-item.text-danger {
        color: #dc3545; /* Red color for logout */
    }

    .dropdown-item.text-danger:hover {
        background-color: #f8d7da; /* Light red background on hover */
        color: #dc3545; /* Keep red text on hover */
    }

    .dropdown-divider {
        margin: 8px 0; /* Add margin to the divider */
        border-top: 1px solid #e9ecef; /* Light border color */
    }

    /* Profile Picture Styling */
    .rounded-circle {
        border: 2px solid #26988c; /* Add a border to the profile picture */
        transition: transform 0.3s ease; /* Smooth transition */
    }

    .rounded-circle:hover {
        transform: scale(1.1); /* Slightly enlarge on hover */
    }

    /* Dropdown Toggle Arrow Styling */
    .dropdown-toggle::after {
        vertical-align: middle; /* Align the arrow vertically */
        margin-left: 8px; /* Add space between icon and arrow */
    }
</style>

<!-- 
Essential Scripts
=====================================-->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 4.3.2 -->
<script src="plugins/bootstrap/js/popper.js"></script>
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- Slick Slider -->
<script src="plugins/slick-carousel/slick/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

<!-- Counterup -->
<script src="plugins/counterup/jquery.easing.js"></script>
<script src="plugins/counterup/jquery.waypoints.min.js"></script>
<script src="plugins/counterup/jquery.counterup.min.js"></script>

<!-- Shuffle JS -->
<script src="plugins/shuffle/shuffle.min.js"></script>

<!-- FontAwesome -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Google Map -->
<script src="plugins/google-map/map.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkeLMlsiwzp6b3Gnaxd86lvakimwGA6UA&callback=initMap"></script>

<!-- Custom Scripts -->
<script src="js/script.js"></script>
<script src="js/contact.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
</body>
</html>