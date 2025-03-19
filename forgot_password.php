<?php
session_start();
include('connect.php'); // Ensure this file connects to the database

$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_otp'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate OTP
        $otp = rand(1000, 9999);

        // Store OTP and email in session
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send OTP via Email
        include('smtp/PHPMailerAutoload.php');
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'lekharsavbose@gmail.com'; // Replace with your email
            $mail->Password = 'zhaxpeydxrfhdxad'; // Replace with your app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('lekharsavbose@gmail.com', 'Vaidhyahub');
            $mail->addAddress($email);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body = 'Your OTP for password reset is: ' . $otp;

            $mail->send();
            header("Location: verify_otp.php"); // Redirect to OTP verification page
            exit();
        } catch (Exception $e) {
            $msg = "Failed to send OTP. Please try again.";
        }
    } else {
        $msg = "Email not found. Please enter a registered email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #1faeaa, #0e8386);
            font-family: Arial, sans-serif;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .input-group {
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
        }

        .btn {
            width: 100%;
            background: #1faeaa;
            border: none;
            padding: 12px;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #0e8386;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form method="POST">
            <div class="input-group">
                <label for="email">Enter your email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="btn" name="send_otp">Send OTP</button>
            <?php if ($msg != ''): ?>
                <p class="error-message"><?php echo $msg; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>