<?php
session_start();
include('connect.php'); // Ensure this file correctly connects to the database.

$msg = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hashing password

    $sql = "SELECT * FROM users WHERE email=? AND password=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['USER_LOGIN'] = 'yes';
        $_SESSION['logedin'] = true;
        $_SESSION['uid'] = $row['u_id'];
        $_SESSION['name'] = $row['u_name'];
        $_SESSION['email'] = $row['email'];

        echo "<script>window.location.href = 'index.php';</script>";
        exit();
    } else {
        $msg = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MedStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Body */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #43A047, #2E7D32);
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        /* Login Container */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        /* Login Box */
        .login-box {
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
            transform: translateY(-20px);
            opacity: 0;
            animation: slideIn 1s ease-in-out forwards;
        }

        /* Logo */
        .logo {
            width: 100px;
            margin-bottom: 10px;
            animation: fadeIn 1.5s ease-in-out;
        }

        h1 {
            color: #2E7D32;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .subtext {
            color: #555;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* Input Group */
        .input-group {
            text-align: left;
            margin-bottom: 15px;
            opacity: 0;
            animation: fadeInUp 1s ease-in-out forwards;
        }

        .input-group:nth-child(1) { animation-delay: 0.3s; }
        .input-group:nth-child(2) { animation-delay: 0.5s; }

        .input-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        /* Button */
        button {
            width: 100%;
            background: #2E7D32;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s ease-in-out;
            opacity: 0;
            animation: fadeInUp 1s ease-in-out forwards;
            animation-delay: 0.7s;
        }

        button:hover {
            background: #1B5E20;
            transform: scale(1.05);
        }

        /* Links */
        .links {
            margin-top: 15px;
            opacity: 0;
            animation: fadeInUp 1s ease-in-out forwards;
            animation-delay: 0.9s;
        }

        .links a {
            color: #2E7D32;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        /* Error Message */
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <!-- Logo -->
            <img src="images/loge.png" alt="MedStore Logo" class="logo">

            <h1>Welcome Back</h1>
            <p class="subtext">Login to continue</p>

            <form method="POST">
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" name="login">Login</button>

                <?php if ($msg != '') { ?>
                    <p class="error-message"><?php echo $msg; ?></p>
                <?php } ?>

                <div class="links">
                    <a href="forgot_password.php">Forgot Password?</a> | 
                    <a href="signup.php">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
