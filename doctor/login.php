<?php
session_start();
include '../connect.php'; // Include the database connection

$msg = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash the password (use better hashing in production)

    $sql = "SELECT * FROM doctors WHERE email = ? AND password = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['DOCTOR_LOGIN'] = 'yes';
        $_SESSION['DOCTOR_ID'] = $row['d_id'];
        $_SESSION['DOCTOR_NAME'] = $row['d_name'];
        $_SESSION['DOCTOR_EMAIL'] = $row['email'];

        header("Location: home.php");
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
    <title>Doctor Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #43A047, #2E7D32);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-box h2 {
            color: #2E7D32;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
            padding: 10px;
        }
        .btn-primary {
            background: #2E7D32;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background: #1B5E20;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Doctor Login</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
            <?php if ($msg != '') { ?>
                <p class="error-message"><?php echo $msg; ?></p>
            <?php } ?>
        </form>
    </div>
</body>
</html>