<?php
session_start();
include('connect.php');

$user_logged_in = isset($_SESSION['uid']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews & Ratings</title>
    
    <!-- External Styles & Scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <!-- Custom Styles -->
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h2 {
            color: #0072ff;
            font-size: 26px;
            margin-bottom: 20px;
        }

        /* Carousel Styles */
        .review-carousel {
            margin-bottom: 20px;
        }
        .review-box {
            padding: 20px;
            border-radius: 10px;
            background: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .review-author {
            font-weight: bold;
            color: #0072ff;
            font-size: 18px;
        }
        .rating {
            color: gold;
            font-size: 18px;
            margin-top: 5px;
        }
        .review-text {
            font-size: 14px;
            color: #555;
            margin-top: 10px;
        }
        .timestamp {
            font-size: 12px;
            color: gray;
        }

        /* Review Form */
        .review-form {
            display: <?= $user_logged_in ? 'block' : 'none' ?>;
            text-align: left;
            margin-top: 20px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 10px;
        }
        .star {
            font-size: 22px;
            cursor: pointer;
            color: #ccc;
        }
        .star:hover, .star.active {
            color: gold;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            resize: none;
            margin-top: 10px;
        }
        .btn {
            background: #0072ff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }
        .btn:hover {
            background: #0056d2;
        }

        /* Login Message */
        .login-msg {
            text-align: center;
            margin-top: 20px;
            color: red;
            display: <?= !$user_logged_in ? 'block' : 'none' ?>;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Reviews & Ratings</h2>

    <!-- Carousel for Reviews -->
    <div class="review-carousel">
        <?php
        $sql = "SELECT * FROM reviews ORDER BY created_at DESC";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='review-box'>";
                echo "<p class='review-author'>" . htmlspecialchars($row['user_name']) . "</p>";
                echo "<p class='rating'>" . str_repeat("⭐", $row['rating']) . "</p>";
                echo "<p class='review-text'>" . htmlspecialchars($row['review_text']) . "</p>";
                echo "<p class='timestamp'>" . $row['created_at'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center;'>No reviews yet.</p>";
        }
        ?>
    </div>

    <!-- Login Message for Non-Logged Users -->
    <?php if (!$user_logged_in): ?>
        <p class="login-msg">You must <a href="login.php">Login</a> to submit a review.</p>
    <?php else: ?>
        <!-- Review Form -->
        <div class="review-form">
            <h3>Give Your Review</h3>
            <form id="reviewForm">
                <input type="hidden" name="user_id" value="<?= $_SESSION['uid'] ?>">
                <input type="hidden" name="user_name" value="<?= $_SESSION['name'] ?>">

                <label>Rating:</label>
                <div class="star-rating">
                    <span class="star" data-value="1">&#9733;</span>
                    <span class="star" data-value="2">&#9733;</span>
                    <span class="star" data-value="3">&#9733;</span>
                    <span class="star" data-value="4">&#9733;</span>
                    <span class="star" data-value="5">&#9733;</span>
                    <input type="hidden" name="rating" id="rating" value="5">
                </div>

                <textarea name="review_text" id="review_text" required placeholder="Write your review..."></textarea>

                <button type="submit" class="btn">Submit Review</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // Initialize Slick Carousel
    $('.review-carousel').slick({
        dots: true,
        infinite: true,
        speed: 600,
        slidesToShow: 1,
        adaptiveHeight: true,
        autoplay: true,
        autoplaySpeed: 2500,
        arrows: false
    });

    // Star rating selection
    $(".star").click(function() {
        $(".star").removeClass("active");
        $(this).addClass("active");
        $(this).prevAll().addClass("active");
        $("#rating").val($(this).attr("data-value"));
    });

    // Submit Review via AJAX
    $("#reviewForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "submit_review.php",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                alert(response);
                location.reload(); // Reload page to show the new review
            }
        });
    });
});
</script>

</body>
</html>
