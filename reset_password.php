<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: forgot_password.php"); // Redirect if email is not set
    exit();
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $new_password = md5($_POST['new_password']); // Hash the new password
    $email = $_SESSION['email'];

    // Update the password in the database
    include('connect.php');
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        session_destroy(); // Clear session data
        $msg = "Password reset successfully. <a href='login.php'>Login</a> with your new password.";
    } else {
        $msg = "Failed to reset password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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

        .success-message {
            color: green;
            font-size: 14px;
            margin-top: 10px;
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
        <h2>Reset Password</h2>
        <form method="POST">
            <div class="input-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
            </div>
            <button type="submit" class="btn" name="reset_password">Reset Password</button>
            <?php if ($msg != ''): ?>
                <p class="<?php echo strpos($msg, 'successfully') !== false ? 'success-message' : 'error-message'; ?>"><?php echo $msg; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>