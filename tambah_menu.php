<?php
include 'koneksi.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    // Pastikan folder uploads ada
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $filename = time() . "_" . basename($gambar);
    $target_file = $target_dir . $filename;

    // Coba upload file
    if (move_uploaded_file($tmp, $target_file)) {
        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO menu (nama, deskripsi, harga, gambar) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $nama, $deskripsi, $harga, $filename);
        if ($stmt->execute()) {
            $message = "✅ Menu berhasil ditambahkan!";
        } else {
            $message = "❌ Gagal menambahkan menu.";
        }
    } else {
        $message = "❌ Gagal upload gambar. Pastikan folder uploads ada.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>➕ Tambah Menu Makanan</h1>
    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nama" required placeholder="Nama makanan">
        <textarea name="deskripsi" required placeholder="Deskripsi singkat"></textarea>
        <input type="number" name="harga" required placeholder="Harga (Rp)">
        <input type="file" name="gambar" required>
        <button type="submit">Simpan</button>
    </form>
    <a href="index.php" class="btn">← Kembali</a>
</body>
</html>
