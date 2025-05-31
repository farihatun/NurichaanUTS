<?php
include 'koneksi.php';
$message = "";
$id = $_GET['id'] ?? 0;
$menu = [];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM menu WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $menu = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    // Jika ada file baru diupload
    if ($gambar) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $filename = time() . "_" . basename($gambar);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($tmp, $target_file)) {
            // Hapus file lama jika ada
            if ($menu['gambar'] && file_exists("uploads/" . $menu['gambar'])) {
                unlink("uploads/" . $menu['gambar']);
            }
            $gambar = $filename;
        } else {
            $message = "❌ Gagal upload gambar.";
            $gambar = $menu['gambar']; // Gunakan gambar lama jika gagal
        }
    } else {
        $gambar = $menu['gambar']; // Gunakan gambar lama jika tidak ada upload baru
    }

    $stmt = $conn->prepare("UPDATE menu SET nama = ?, deskripsi = ?, harga = ?, gambar = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $nama, $deskripsi, $harga, $gambar, $id);
    if ($stmt->execute()) {
        $message = "✅ Menu berhasil diupdate!";
        header("Refresh:1; url=index.php");
    } else {
        $message = "❌ Gagal update menu.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>✏️ Edit Menu Makanan</h1>
    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nama" required placeholder="Nama makanan" value="<?= htmlspecialchars($menu['nama'] ?? '') ?>">
        <textarea name="deskripsi" required placeholder="Deskripsi singkat"><?= htmlspecialchars($menu['deskripsi'] ?? '') ?></textarea>
        <input type="number" name="harga" required placeholder="Harga (Rp)" value="<?= htmlspecialchars($menu['harga'] ?? '') ?>">
        <img src="uploads/<?= $menu['gambar'] ?? '' ?>" width="100" style="margin: 10px 0; display: block;">
        <input type="file" name="gambar">
        <button type="submit">Simpan</button>
    </form>
    <a href="index.php" class="btn">← Kembali</a>
</body>
</html>
