<?php
if(isset($_POST['submit'])) {
    $nama_file = time() . '_' . basename($_FILES['gambar']['name']);
    $target_dir = "uploads/";
    $target_file = $target_dir . $nama_file;

    // Debug path
    echo 'Current directory: ' . getcwd() . '<br>';
    echo 'Target file: ' . $target_file . '<br>';
    echo 'Folder uploads ada? ' . (is_dir($target_dir) ? 'YA' : 'TIDAK') . '<br>';

    // Pastikan folder uploads ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
        echo "File berhasil diupload!";
        // Simpan $nama_file ke database jika diperlukan
    } else {
        echo "Gagal upload file.";
    }
}
?>
