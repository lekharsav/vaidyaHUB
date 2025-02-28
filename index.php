
<?php  
include "navbar.php";         
include "connect.php";
$sql="select * from medicine ";
$res=mysqli_query($con,$sql);

?>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<section class="banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-xl-7">
                <div class="block">
                    <div class="divider mb-3 fade-in"></div>
                    <span class="text-uppercase text-sm letter-spacing floating-text text-highlight">Total Health Care Solution</span>
                    <h1 class="mb-3 mt-3 floating-text">Your Most Trusted <span class="text-highlight">Health Partner</span></h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <!-- Appointment Booking -->
            <div class="col-lg-3 col-md-6">
                <div class="card text-center p-4 shadow-sm border-0 rounded-4 feature-card">
                    <div class="feature-icon mb-3">
                        <i class="icofont-surgeon-alt display-5 text-primary"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Appointment Booking</h6>
                    <p class="text-muted small">24/7 Emergency support. We follow the principle of family medicine.</p>
                    <a href="appoinment.php" class="btn btn-primary btn-sm rounded-pill px-4">Book Now</a>
                </div>
            </div>

            <!-- Lab Test Booking -->
            <div class="col-lg-3 col-md-6">
                <div class="card text-center p-4 shadow-sm border-0 rounded-4 feature-card">
                    <div class="feature-icon mb-3">
                        <i class="icofont-laboratory display-5 text-danger"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Book Your Lab Test</h6>
                    <p class="text-muted small">Schedule lab tests at your convenience with trusted diagnostics.</p>
                    <a href="labTest.php" class="btn btn-danger btn-sm rounded-pill px-4">Book Test</a>
                </div>
            </div>

            <!-- Room Booking -->
            <div class="col-lg-3 col-md-6">
                <div class="card text-center p-4 shadow-sm border-0 rounded-4 feature-card">
                    <div class="feature-icon mb-3">
                        <i class="icofont-bed display-5 text-success"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Book a Room</h6>
                    <p class="text-muted small">Comfortable treatment rooms for better recovery and care.</p>
                    <a href="roomBooking.php" class="btn btn-success btn-sm rounded-pill px-4">Book Room</a>
                </div>
            </div>

            <!-- Medicine Purchase -->
            <div class="col-lg-3 col-md-6">
                <div class="card text-center p-4 shadow-sm border-0 rounded-4 feature-card">
                    <div class="feature-icon mb-3">
					<i class="icofont-pills display-5 text-warning"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Purchase Medicine</h6>
                    <p class="text-muted small">Order prescribed medicines with fast and secure delivery.</p>
                    <a href="medicine.php" class="btn btn-warning btn-sm rounded-pill px-4 text-white">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</section>






<section class="products" id="products">
    <h3 class="text-center">Featured Products</h3>

    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <?php while($row = mysqli_fetch_assoc($res)) { ?>
                <div class="swiper-slide">
                    <div class="product">
                        <img src="product/<?php echo $row['image']; ?>" alt="Medicine Image">
                        <h4><?php echo $row['name']; ?></h4>
                        <p><?php echo $row['description']; ?></p>
                        <p class="price">Rs <?php echo number_format($row['price'], 2); ?></p>
                        <a href="buy.php?id=<?php echo $row['id']; ?>" class="btn">Buy Now</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Swiper Navigation Buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<section class="section service gray-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="section-title">
                    <h2>Award-Winning Patient Care</h2>
                    <div class="divider mx-auto my-4"></div>
                    <p>Discover our exceptional healthcare services, providing the best medical solutions with expert professionals.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Service Cards -->
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item">
                    <div class="icon">
                        <i class="icofont-laboratory"></i>
                    </div>
                    <h4>Laboratory Services</h4>
                    <p>Advanced lab tests with accurate results.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item">
                    <div class="icon">
                        <i class="icofont-heart-beat-alt"></i>
                    </div>
                    <h4>Heart Disease</h4>
                    <p>Comprehensive heart care and treatment.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item">
                    <div class="icon">
                        <i class="icofont-tooth"></i>
                    </div>
                    <h4>Dental Care</h4>
                    <p>Expert dental services for a healthy smile.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item">
                    <div class="icon">
                        <i class="icofont-crutch"></i>
                    </div>
                    <h4>Body Surgery</h4>
                    <p>Advanced surgical procedures with expert surgeons.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item">
                    <div class="icon">
                        <i class="icofont-brain-alt"></i>
                    </div>
                    <h4>Neurology Surgery</h4>
                    <p>Innovative solutions for brain and nervous system disorders.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item">
                    <div class="icon">
                        <i class="icofont-dna-alt-1"></i>
                    </div>
                    <h4>Gynecology</h4>
                    <p>Specialized women’s healthcare services.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<
<?php include 'footer.php'; ?>
<script>
  var swiper = new Swiper(".mySwiper", {
        slidesPerView: 3,
        spaceBetween: 15,
        loop: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            768: { slidesPerView: 2 },
            480: { slidesPerView: 1 }
        }
    });
</script>
 
<style>
   /* ============================ */
/* 🎨 BUTTON STYLES */
/* ============================ */

/* Soft pastel colors for buttons */
.btn-primary { background-color: #5A9EF0; border: none; }
.btn-danger { background-color: #FF6B6B; border: none; }
.btn-success { background-color: #7AC74F; border: none; }
.btn-warning { background-color: #FFC107; border: none; }

/* Button hover effect */
.btn {
    transition: opacity 0.3s ease-in-out;
}
.btn:hover {
    opacity: 0.85;
}

/* ============================ */
/* 🌟 FEATURE CARDS */
/* ============================ */

.feature-card {
    transition: all 0.3s ease-in-out;
}
.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
}

/* ============================ */
/* 🌊 ANIMATIONS */
/* ============================ */

/* Floating animation */
@keyframes floatAnimation {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}
.floating-text {
    animation: floatAnimation 3s ease-in-out infinite;
}

/* Fade-in animation */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeIn 1.5s ease-out forwards;
}
@keyframes fadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ============================ */
/* 🏥 SERVICE ITEMS */
/* ============================ */

.service-item {
    background: white;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    min-height: 220px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.service-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}
.icon i {
    font-size: 50px;
    color: #00bcd4; /* Light blue healthcare theme */
}
h4 {
    font-weight: bold;
    color: #333;
    margin-top: 15px;
}
.content p {
    font-size: 14px;
    color: #666;
    margin-top: 5px;
}
.gray-bg {
    background: #f8f9fa;
    padding: 60px 0;
}

/* ============================ */
/* 🛍️ PRODUCT BOX STYLES */
/* ============================ */

.products-container {
    text-align: center;
    margin: 20px auto;
    max-width: 900px; /* Medium-size box */
}
.products-box {
    background: #ffffff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: auto;
}

/* ============================ */
/* 🎠 SWIPER CAROUSEL */
/* ============================ */

.swiper {
    padding: 20px 0;
}
.swiper-slide {
    display: flex;
    justify-content: center;
}

/* ============================ */
/* 🏷️ PRODUCT CARD */
/* ============================ */

.product {
    width: 200px;
    padding: 15px;
    background: #f8f8f8;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s;
}
.product:hover {
    transform: translateY(-5px);
}
.product img {
    height: 90px;
    width: 90px;
    object-fit: cover;
    border-radius: 5px;
}
.product h4 {
    font-size: 16px;
    margin-top: 10px;
}
.product p {
    font-size: 14px;
    color: #666;
}
.product .price {
    font-weight: bold;
    color: #00897B;
}

/* ============================ */
/* 🛒 BUY BUTTON */
/* ============================ */

.btn {
    background: #00897B;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    display: inline-block;
    transition: background 0.3s;
}
.btn:hover {
    background: #00796B;
}

</style>
  
   