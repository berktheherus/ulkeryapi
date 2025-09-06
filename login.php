<?php
session_start();

// If the user is already logged in, redirect to the admin page
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: admin.php');
    exit;
}

$error_message = '';
$password = 'test'; // The password

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password']) && $_POST['password'] === $password) {
        // Password is correct, start the session
        $_SESSION['loggedin'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error_message = 'Invalid password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Ülker Yapı</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9f7f3;
            margin: 0;
        }
        .login-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(44, 44, 44, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h1 {
            font-family: 'Playfair Display', serif;
            color: #b38f29;
            margin-bottom: 20px;
        }
        .login-form input[type="password"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            font-size: 16px;
        }
        .login-form .cta-button {
            width: 100%;
            cursor: pointer;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <p>Please enter the password to access the messages.</p>
        <form class="login-form" method="POST" action="login.php">
            <?php if ($error_message): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <button type="submit" class="cta-button">Login</button>
        </form>
    </div>
</body>
</html>
