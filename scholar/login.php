<?php
session_start();
include 'db.php'; // Make sure this connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        // Fetch user by email
        $stmt = $conn->prepare("SELECT * FROM users1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['degree']  = $user['degree'];

                // Redirect based on degree
                switch ($user['degree']) {
                    case 'bachelor':
                        header("Location: scholarship_bsc.php");
                        break;
                    case 'master':
                        header("Location: scholarship_msc.php");
                        break;
                    case 'phd':
                        header("Location: scholarship_phd.php");
                        break;
                    default:
                        header("Location: scholarship.php"); // fallback
                }
                exit();
            } else {
                echo "<script>alert('Incorrect password'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('User not found'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Please fill all fields'); window.location.href='login.php';</script>";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 500px;
            margin: 90px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-dark text-white">
    <div class="btn-btn-primary">
        
    </div>

<div class="container ">
    <div class="form-container bg-dark">
        <h3 class="text-center mb-4 text-primary">Login Here Scholar</h3>
       
       <form action="" method="POST"> <div class="mb-3"> <label for="email" class="form-label">Email Address</label> <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required> </div> <div class="mb-3"> <label for="password" class="form-label">Password</label> <input type="password" class="form-control" id="password" name="password" required> </div> <button type="submit" class="btn btn-primary w-100">Login</button> <br /> <br /> <a href="register.php" style="background-color: blanchedalmond; color: black; text-align: center; font-style: italic; text-decoration: none;">Already have an Account?</a> </form> 

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
