<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: LOGIN.php');
    exit();
}

include 'dbconnection.php'; // Include DB connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $retype_password = $_POST['retype_password'];

    // Password validation to ensure it contains at least one special character and one number
    if (!preg_match('/^(?=.*[!@#$%^&*])(?=.*\d).{8,}$/', $new_password)) {
        $message[] = "Password must be at least 8 characters long, contain at least a special character, and a number.";
    } else {
        // Check if new password and retype password match
        if ($new_password == $retype_password) {
            // Update the password in the database
            $email = $_SESSION['email'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Secure password hash

            $sql = "UPDATE users SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hashed_password, $email);
            
            if ($stmt->execute()) {
                echo "<script>
                        alert('Password reset successfully!');
                        window.location.href = 'LOGIN.php';
                      </script>";
                exit(); // Make sure the script stops after redirection
            } else {
                $message[] ="Error updating password!";
            }
        } else {
            $message[] ="Passwords does not match!";
        }
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
            <h1>Reset-Password</h1>
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo "<div class='message'>$msg</div>";
                }
            }
            ?>  
            <form name="f1" action="#" method="post">
                <input type="new_password" name="new_password" placeholder="New-password" required>
                <input type="retype_password" name="retype_password" placeholder="Retype-Password" required>

                <button name="submit" type="submit">Reset</button>
            </form>
        </div>
    </div>
  
  </body>
</html>

