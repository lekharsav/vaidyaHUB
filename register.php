<?php

include('includes/db.php'); //
include('smtp/PHPMailerAutoload.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Hash password using MD5
    $hashed_password = md5($password);  // ✅ Fixed MD5 hashing

    $otp = rand(1000, 9999);

    // Store data in session for verification
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $name;
    $_SESSION['phone'] = $phone;
    $_SESSION['hashed_password'] = $hashed_password;

    // Send OTP Email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'greenery.freshh@gmail.com'; 
        $mail->Password = 'kcmeicsiauxfvftd'; // Use your app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('greenery.freshh@gmail.com', 'medimicecare');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = 'Your OTP for account verification is: ' . $otp;

        $mail->send();
    } catch (Exception $e) {
        die('Email could not be sent. Mailer Error: ' . $mail->ErrorInfo);
    }
}
?>
<?php if (isset($_SESSION['otp'])): ?>
<!-- OTP Verification Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Basic Page Styling */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #e3f2fd;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        /* OTP Container */
        .container {
            width: 100%;
            max-width: 380px;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Title */
        h2 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        /* Input Field */
        .input-group {
            position: relative;
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #0288d1;
            box-shadow: 0 0 5px rgba(2, 136, 209, 0.5);
        }

        /* Verify Button */
        .btn {
            width: 100%;
            background: #0288d1;
            border: none;
            padding: 12px;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            background: #026aa7;
            transform: scale(1.03);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter OTP</h2>
        <form method="POST" action="verify_otp.php">
            <div class="input-group">
                <label for="otp">OTP Code</label>
                <input type="text" name="otp" id="otp" required placeholder="Enter OTP">
            </div>
            <button type="submit" class="btn" name="verify">Verify OTP</button>
        </form>
    </div>
</body>
</html>
<?php endif; ?>
