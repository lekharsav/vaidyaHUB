<?php
session_start();
include('includes/db.php'); // Ensure this is correct

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
        $_SESSION['USER_LOGIN']='yes';
 
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
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #E3F2FD; /* Light Blue */
        }

        /* Container */
        .container {
            width: 100%;
            max-width: 350px;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Title */
        .title {
            font-size: 22px;
            font-weight: bold;
            color: #00897B; /* Medical Green */
            margin-bottom: 15px;
        }

        /* Input Fields */
        .input-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .input-group label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Button */
        .btn {
            width: 100%;
            background: #00897B;
            border: none;
            padding: 10px;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background: #00796B;
        }

        /* Links */
        .forgot-password,
        .signup-link {
            display: block;
            font-size: 14px;
            color: #00897B;
            margin-top: 10px;
            text-decoration: none;
        }

        .forgot-password:hover,
        .signup-link:hover {
            text-decoration: underline;
        }

        /* Error Message */
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="title">Login</h2>

        <form method="POST">
            <!-- Email -->
            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <!-- Password -->
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <input type="submit" value="Login" class="btn" name="login">

            <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
            <p>Don't have an account? <a href="signup.php" class="signup-link">Sign Up</a></p>

            <p class="error-message"></p>
        </form>
    </div>
</body>
</html>
