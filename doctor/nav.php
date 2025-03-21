<nav class="navbar navbar-expand-lg navbar-dark doctor-navbar">
        <div class="container">
            <a class="navbar-brand" href="home.php">Doctor Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointments.php">Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reviews.php">Reviews</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <style>
         .doctor-navbar {
            background: #26988c;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .doctor-navbar .navbar-brand {
            color: #fff !important;
            font-weight: 600;
        }
        .doctor-navbar .nav-link {
            color: #fff !important;
        }
    </style>