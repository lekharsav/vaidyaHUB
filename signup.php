<?php 
include('connect.php'); // Ensure this is correct
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up | MedStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #1faeaa, #0e8386);
        }

      
        .container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

      
        .logo {
            width: 80px;
            margin-bottom: 10px;
        }

    
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

      
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
            border-color: #1faeaa;
            box-shadow: 0 0 8px rgba(31, 174, 170, 0.5);
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
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn:hover {
            background: #0e8386;
            transform: scale(1.05);
            box-shadow: 0px 5px 10px rgba(15, 130, 134, 0.3);
        }

       
        .signin-link {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #1faeaa;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .signin-link:hover {
            text-decoration: underline;
            color: #0e8386;
        }

       
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

     
        @media (max-width: 450px) {
            .container {
                max-width: 90%;
                padding: 15px;
            }
        }
    </style>
</head>  
<body>
    <div class="container">
        <img src="images/loge.png" alt="MedStore Logo" class="logo">
        <form action="register.php" method="POST">
            <h2 class="title">Sign Up</h2>

            <!-- Username -->
            <div class="input-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>

            <!-- Email -->
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <!-- Phone Number -->
            <div class="input-group">
                <label for="phone">Mobile Number</label>
                <input type="text" id="phone" name="phone" placeholder="Enter your mobile number" required>
            </div>

            <!-- Password -->
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <input type="submit" value="Sign Up" class="btn" name="signup">

            <p>Already have an account? <a href="login.php" class="signin-link">Sign In</a></p>
        </form>
    </div>
</body>
</html>
