<?php
session_start();
if (!isset($_SESSION['login'])) header("Location: ../index.php");

require '../config/database.php';
require '../controllers/GambarController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new GambarController((new Database())->connect());
    if ($controller->upload($_POST, $_FILES['gambar'])) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Gagal upload!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style.css">
    <title>Upload</title>
</head>
<body class="auth-page">
    <form method="POST" enctype="multipart/form-data">
        <h2>Upload Gambar</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <input type="text" name="judul" placeholder="Judul" required>
        <input type="text" name="album" placeholder="Album">
        <textarea name="deskripsi" placeholder="Deskripsi..."></textarea>
        <input type="date" name="tanggal" required>
        <input type="file" name="gambar[]" multiple required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
