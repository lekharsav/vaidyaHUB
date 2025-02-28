<?php
session_start();
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


  <header>
    <!-- Top Bar -->
   
    <!-- Fixed Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light   fixed-top py-1" style="background-color:#d3d8d8d9;">
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
                    <li class="nav-item"><a class="nav-link text-dark" href="service.php">Services</a></li>


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
                        <a href="cart.php" class="nav-link text-dark position-relative">
                            <i class="icofont-cart fs-4"></i>
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="cart-count">3</span>
                        </a>
                    </li>

                    <!-- Profile Button with Pop-out Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link text-dark d-flex align-items-center dropdown-toggle" href="#" id="profileMenu" data-bs-toggle="dropdown">
                            <i class="icofont-user-alt-5 fs-4 me-2"></i>
                        </a>
                        <ul class="dropdown-menu border-0 shadow p-3">
    <?php if (isset($_SESSION['USER_LOGIN']) && $_SESSION['USER_LOGIN'] === 'yes') { ?>
        <li><a class="dropdown-item" href="profile.php">My Account</a></li>
        <li><a class="dropdown-item" href="add-account.php">Add Another Account</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
    <?php } else { ?>
        <li><a class="dropdown-item" href="login.php">Login</a></li>
    <?php } ?>
</ul>

                    </li>
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
    .dropdown-menu {
        min-width: 200px;
        border-radius: 10px;
    }

    .dropdown-menu a {
        padding: 10px;
        font-size: 14px;
    }

    .dropdown-menu a:hover {
        background-color: #f8f9fa;
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

  
</body>
</html>