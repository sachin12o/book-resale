<?php

@include 'dbconnection.php';

session_start();

if(isset($_POST['submit'])) {

    // Sanitize email and password inputs
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = mysqli_real_escape_string($conn, $filter_email);
    $filter_pass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Fetch the user data based on the provided email
    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('Query failed');

    if (mysqli_num_rows($select_users) > 0) {

        // Get the user data from the database
        $row = mysqli_fetch_assoc($select_users);

        // Verify the entered password with the stored hashed password
        if (password_verify($filter_pass, $row['password'])) {

            // If the user is an admin
            if($row['user_type'] === 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['id'];

                echo "<script>
                        alert('Login successful!');
                        window.location.href = 'admin_page.php';
                    </script>";
                exit();

            // If the user is a normal user
            } elseif ($row['user_type'] == 'user') {
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];

                echo "<script>
                        alert('Login successful!');
                        window.location.href = 'Home.php';
                    </script>";
                exit();

            } else {
                $message[] = 'No user found!';
            }

        } else {
            $message[] = 'Incorrect email or password!';
        }

    } else {
        $message[] = 'No user found with that email!';
    }

}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login_style.css">
    <style>
        body {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    background: url('hardhd.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>LOGIN</h1>
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo "<div class='message'>$msg</div>";
                }
            }
            ?>   
            <form name="f1" onsubmit="return validation()" action="#" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <div class="password-wrapper">
                    <input type="password" name="password" placeholder="Password" id="password" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">Show</button>
                </div>
                <button name="submit" type="submit">Login</button>
            </form>
            <div class="Rform">
                <a href="registration_form.php">Register</a>
                <a href="forget_password.php">Forgot Password?</a>
            </div>
        </div>
    </div>

    <script>
      
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleButton = document.querySelector('.toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleButton.textContent = 'Hide';
            } else {
                passwordField.type = 'password';
                toggleButton.textContent = 'Show';
            }
        }
    </script>
</body>
</html>
