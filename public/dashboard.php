<?php
session_start();
if (!isset($_SESSION['login'])) header("Location: ../index.php");

require '../config/database.php';
$db = (new Database())->connect();

require '../models/Gambar.php';
$gambar = new Gambar($db);
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../style.css">
    <title>Dashboard Galeri</title>
</head>

<body>
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <img id="modal-img" src="" alt="">
            <h3 id="modal-judul"></h3>
            <p><b>Album:</b> <span id="modal-album"></span></p>
            <p><b>Tanggal:</b> <span id="modal-tanggal"></span></p>
            <p id="modal-deskripsi"></p>
        </div>
    </div>
    <div class="topbar">
        <h2>Galeri Ayufi</h2>
        <div>
            <a href="upload.php">+ Upload</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <?php
    $search = $_GET['search'] ?? '';
    $albumFilter = $_GET['album'] ?? '';
    $tanggalFilter = $_GET['tanggal'] ?? '';

    // Query dinamis
    $where = "1";
    if ($search) $where .= " AND judul LIKE '%$search%'";
    if ($albumFilter) $where .= " AND album = '$albumFilter'";
    if ($tanggalFilter) $where .= " AND tanggal = '$tanggalFilter'";

    $data = $db->query("SELECT * FROM gambar WHERE $where ORDER BY created_at DESC");

    // Untuk dropdown album
    $albums = $db->query("SELECT DISTINCT album FROM gambar WHERE album != '' ORDER BY album ASC");
    ?>


    <?php
    $daftarAlbum = $db->query("SELECT album, COUNT(*) as total FROM gambar WHERE album != '' GROUP BY album");
    ?>

    <div class="album-list">
        <strong>Album:</strong>
        <a href="dashboard.php" class="<?= !$albumFilter ? 'active' : '' ?>">Semua</a>
        <?php while ($a = $daftarAlbum->fetch_assoc()): ?>
            <a href="dashboard.php?album=<?= urlencode($a['album']) ?>" class="<?= ($albumFilter == $a['album']) ? 'active' : '' ?>">
                📁 <?= htmlspecialchars($a['album']) ?> (<?= $a['total'] ?>)
            </a>
        <?php endwhile; ?>
    </div>


    <div class="gallery">
        <?php while ($g = $data->fetch_assoc()): ?>
            <div class="card">
                <img
                    src="../uploads/<?= $g['nama_file'] ?>"
                    alt="<?= $g['judul'] ?>"
                    data-judul="<?= htmlspecialchars($g['judul']) ?>"
                    data-album="<?= htmlspecialchars($g['album']) ?>"
                    data-deskripsi="<?= htmlspecialchars($g['deskripsi']) ?>"
                    data-tanggal="<?= $g['tanggal'] ?>">
                <h3><?= $g['judul'] ?></h3>
                <p><?= $g['album'] ?> - <?= $g['tanggal'] ?></p>
                <div class="actions">
                    <a href="edit.php?id=<?= $g['id'] ?>">✏️</a>
                    <a href="delete.php?id=<?= $g['id'] ?>" onclick="return confirm('Hapus gambar ini?')">🗑️</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <script>
        document.querySelectorAll('.card img').forEach(img => {
            img.addEventListener('click', () => {
                document.getElementById('modal').style.display = 'flex';
                document.getElementById('modal-img').src = img.src;
                document.getElementById('modal-judul').innerText = img.dataset.judul;
                document.getElementById('modal-album').innerText = img.dataset.album;
                document.getElementById('modal-tanggal').innerText = img.dataset.tanggal;
                document.getElementById('modal-deskripsi').innerText = img.dataset.deskripsi;
            });
        });

        document.querySelector('.close-btn').onclick = () => {
            document.getElementById('modal').style.display = 'none';
        };

        window.onclick = (e) => {
            if (e.target == document.getElementById('modal')) {
                document.getElementById('modal').style.display = 'none';
            }
        };
    </script>

</body>

</html>