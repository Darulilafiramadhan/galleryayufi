<?php
session_start();
require 'config/database.php';
require 'controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController((new Database())->connect());
    if ($auth->register($_POST['username'], $_POST['password'])) {
        $_SESSION['login'] = true;
        header("Location: public/dashboard.php");
    } else {
        $error = "Username sudah dipakai!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body class="auth-page">
    <form method="POST">
        <h2>Register</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Daftar</button>
        <p>Sudah punya akun? <a href="index.php">Login</a></p>
    </form>
</body>
</html>
