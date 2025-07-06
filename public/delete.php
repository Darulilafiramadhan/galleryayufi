<?php
session_start();
if (!isset($_SESSION['login'])) header("Location: ../index.php");

require '../config/database.php';
$db = (new Database())->connect();

$id = $_GET['id'];
// Ambil nama file dulu
$gambar = $db->query("SELECT nama_file FROM gambar WHERE id=$id")->fetch_assoc();
if ($gambar) {
    $file = '../uploads/' . $gambar['nama_file'];
    if (file_exists($file)) unlink($file); // Hapus file dari folder
    $db->query("DELETE FROM gambar WHERE id=$id"); // Hapus dari DB
}
header("Location: dashboard.php");
