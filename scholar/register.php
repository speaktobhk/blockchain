<?php
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name'] ?? '');
    $phone    = trim($_POST['phone_no'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $degree   = $_POST['degree'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($name && $phone && $email && $degree && $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users1 WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "<script>alert('Email already registered'); window.location.href='register.php';</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO users1 (name, phone_no, email, password, degree) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $phone, $email, $hashedPassword, $degree);

            if ($stmt->execute()) {
                // Redirect with success message
                header("Location: login.php?success=1");
                exit();
            } else {
                echo "<script>alert('Registration failed. Try again.'); window.location.href='register.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Please fill all fields'); window.location.href='register.php';</script>";
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
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            opacity: 0;
            animation: fadeIn 7s ease-in-out forwards ;
        }
         /* Define the fade-in animation */
         @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-30px); /* Start above the screen */
            }
            50% {
                opacity: 0.5;
                transform: translateY(15px); /* Move down a little */
            }
            100% {
                opacity: 1;
                transform: translateY(0); /* End in normal position */
            }
        }
    </style>
</head>
<body class="bg-dark text-white">
    <div class="btn-btn-primary">
    
    </div>

<div class="container ">
    <div class="form-container bg-dark">
        <h3 class="text-center mb-4 text-primary">Scholarship Registration</h3>
       


        
<form action="register.php" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="tel" class="form-control" id="phone" name="phone_no" placeholder="Enter your phone number" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="degree" class="form-label">Degree Program</label>
        <select class="form-select" id="degree" name="degree" required>
            <option selected disabled>Choose your degree...</option>
            <option value="bachelor">Bachelor’s Degree</option>
            <option value="master">Master’s Degree</option>
            <option value="phd">Ph.D.</option>
            <option value="other">Other</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary w-100">Register</button>
    <br><br>
    <a href="login.php" style="background-color: blanchedalmond; color: black; text-align: center; font-style: italic; text-decoration: none;">Already have an Account?</a>
</form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
