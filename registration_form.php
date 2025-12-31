<?php

@include 'dbconnection.php';

if (isset($_POST['submit'])) {

    // Sanitize and validate name: allow only alphabetic characters
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    if (!preg_match('/^[a-zA-Z]+$/', $filter_name)) {
        echo "<script>alert('Name must contain only letters!');</script>";
        exit(); // Stop further execution if the name is invalid
    }
    $name = mysqli_real_escape_string($conn, $filter_name);

    // Sanitize and validate email
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = mysqli_real_escape_string($conn, $filter_email);

    // Sanitize passwords
    $filter_pass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $filter_cpass = filter_var($_POST['confirm_password'], FILTER_SANITIZE_STRING);

    // Email validation: check if email follows the pattern (@email.com)
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
        echo "<script>alert('Please enter a valid email with @email.com domain.');</script>";
    }
    // Password validation: must be at least 8 characters long, contain a special character, and a number
    elseif (!preg_match('/^(?=.*[!@#$%^&*])(?=.*\d).{8,}$/', $filter_pass)) {
        echo "<script>alert('Password must be at least 8 characters long, contain a special character and a number!');</script>";
    } else {
        // Hash the password using password_hash()
        $pass = password_hash($filter_pass, PASSWORD_DEFAULT); // Using bcrypt by default
        $cpass = password_hash($filter_cpass, PASSWORD_DEFAULT); // Unnecessary, we'll compare plain passwords

        // Check if user already exists
        $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('Query failed');

        if (mysqli_num_rows($select_users) > 0) {
            echo "<script>alert('User already exists!');</script>";
        } elseif ($filter_pass != $filter_cpass) {
            echo "<script>alert('Confirm password does not match!');</script>";
        } else {
            // Insert the user with hashed password
            mysqli_query($conn, "INSERT INTO `users` (name, email, password) VALUES ('$name', '$email', '$pass')") or die('Query failed');
            echo "<script>
                    alert('Registered successfully!');
                    window.location.href = 'LOGIN.php';
                  </script>";
            exit(); // Ensure script stops execution after redirection
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="login_style.css">
    <style>
        .form-container .button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background: #007BFF;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }
        .form-container .button:hover {
         background: #0056b3;
    }
</style>
   
</head>
<body>
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo "
            <div class='message'>
                <span>$msg</span>
                <i class='fas fa-times' onclick='this.parentElement.remove();'></i>
            </div>
            ";
        }
    }
    ?>
    <div class="container">
        <form action="#" method="POST">
            <div class="form-container">
                <h1>Register</h1> 
                <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo "<div class='message'>$msg</div>";
                }
            }
            ?>           
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <div class="password-wrapper">
                    <input type="password" name="password" placeholder="Password" id="password" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">Show</button>
                </div>
                <div class="password-wrapper">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" required>
                    <button type="button" class="toggle-password" onclick="toggleConfirmPasswordVisibility()">Show</button>
                </div>
               
                <input type="submit" class="button" name="submit" value="Register">
                <a href="LOGIN.php">Already have an account? Login</a>
            </div>
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }

        function toggleConfirmPasswordVisibility() {
            const confirmPasswordField = document.getElementById('confirm_password');
            if (confirmPasswordField.type === 'password') {
                confirmPasswordField.type = 'text';
            } else {
                confirmPasswordField.type = 'password';
            }
        }
    </script>
    <?php 
    
    ?>
</body>
</html>
