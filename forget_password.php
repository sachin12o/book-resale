<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnection.php'; // Include DB connection file

    // Get the email input from the form
    $email = $_POST['email'];

    // Email format validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[]= "Please enter a valid email!";
    } else {
        // Check if email exists in the database
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email exists, redirect to the new password page
            $_SESSION['email'] = $email;
            header('Location: new_password.php');
            exit(); // Make sure the script stops after redirection
        } else {
            $message[] = 'No user found!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            <h1>Reset-password</h1>
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo "<div class='message'>$msg</div>";
                }
            }
            ?>   
            <form name="f1" onsubmit="return validation()" action="#" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <button name="submit" type="submit">Submit</button>
                <a href="LOGIN.php">Login Page</a>

            </form>
        </div>
    </div>


</body>
</html>

