<?php
include 'koneksi.php';
$id = $_GET['id'] ?? 0;

if ($id) {
    // Ambil data menu untuk hapus file gambarnya
    $stmt = $conn->prepare("SELECT gambar FROM menu WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $menu = $stmt->get_result()->fetch_assoc();

    // Hapus file gambar jika ada
    if ($menu && $menu['gambar'] && file_exists("uploads/" . $menu['gambar'])) {
        unlink("uploads/" . $menu['gambar']);
    }

    // Hapus data dari database
    $stmt = $conn->prepare("DELETE FROM menu WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php");
exit;
?>
