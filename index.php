<?php
session_start();
if (isset($_SESSION['login'])) header("Location: public/dashboard.php");
require 'config/database.php';
require 'controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController((new Database())->connect());
    if ($auth->login($_POST['username'], $_POST['password'])) {
        $_SESSION['login'] = true;
        header("Location: public/dashboard.php");
        exit;
    } else {
        $error = "username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body class="auth-page">
    <form method="POST">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
    </form>
</body>
</html>
