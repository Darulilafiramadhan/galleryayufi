<?php
class Gambar
{
    private $conn;
    private $table = "gambar";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insert($judul, $album, $nama_file, $deskripsi, $tanggal)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO $this->table (judul, album, nama_file, deskripsi, tanggal)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssss", $judul, $album, $nama_file, $deskripsi, $tanggal);
        return $stmt->execute();
    }

    public function getAll()
    {
        return $this->conn->query("SELECT * FROM $this->table ORDER BY created_at DESC");
    }
}
