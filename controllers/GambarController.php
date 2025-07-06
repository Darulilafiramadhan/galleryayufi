<?php
require_once '../models/Gambar.php';

class GambarController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new Gambar($db);
    }

    public function upload($data, $files) {
        $judul     = $data['judul'];
        $album     = $data['album'];
        $deskripsi = $data['deskripsi'];
        $tanggal   = $data['tanggal'];
    
        $jumlah = count($files['name']);
        $success = 0;
    
        for ($i = 0; $i < $jumlah; $i++) {
            $tmp_name = $files['tmp_name'][$i];
            $original = basename($files['name'][$i]);
            $filename = time() . '_' . $i . '_' . $original;
            $path     = __DIR__ . '/../uploads/' . $filename;
    
            if (move_uploaded_file($tmp_name, $path)) {
                $this->model->insert($judul, $album, $filename, $deskripsi, $tanggal);
                $success++;
            }
        }
    
        return $success > 0;
    }
}    
