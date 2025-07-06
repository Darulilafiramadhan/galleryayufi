<?php
session_start();
if (!isset($_SESSION['login'])) header("Location: ../index.php");

require '../config/database.php';
$db = (new Database())->connect();

// Ambil data
$id = $_GET['id'];
$data = $db->query("SELECT * FROM gambar WHERE id=$id")->fetch_assoc();

if (!$data) die("Gambar tidak ditemukan.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = $_POST['judul'];
    $album     = $_POST['album'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal   = $_POST['tanggal'];

    // Jika upload baru
    if (!empty($_FILES['gambar']['name'])) {
        $namaBaru = time() . '_' . basename($_FILES['gambar']['name']);
        $path = '../uploads/' . $namaBaru;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $path);

        // Hapus lama
        unlink('../uploads/' . $data['nama_file']);

        $db->query("UPDATE gambar SET judul='$judul', album='$album', nama_file='$namaBaru', deskripsi='$deskripsi', tanggal='$tanggal' WHERE id=$id");
    } else {
        $db->query("UPDATE gambar SET judul='$judul', album='$album', deskripsi='$deskripsi', tanggal='$tanggal' WHERE id=$id");
    }

    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style.css">
    <title>Edit Gambar</title>
</head>
<body class="auth-page">
    <form method="POST" enctype="multipart/form-data">
        <h2>Edit Gambar</h2>
        <input type="text" name="judul" value="<?= $data['judul'] ?>" required>
        <input type="text" name="album" value="<?= $data['album'] ?>">
        <textarea name="deskripsi"><?= $data['deskripsi'] ?></textarea>
        <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>" required>
        <p>Gambar sekarang:</p>
        <img src="../uploads/<?= $data['nama_file'] ?>" width="200"><br>
        <input type="file" name="gambar"> (Opsional ganti gambar)<br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
