<?php
include 'koneksi.php';
$menu_id = $_GET['id'] ?? 0;
$menu = $conn->query("SELECT * FROM menu WHERE id = $menu_id")->fetch_assoc();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST['nama'];
    $jumlah = $_POST['jumlah'];

    $stmt = $conn->prepare("INSERT INTO pemesanan (nama_pelanggan, id_menu, jumlah) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $nama, $menu_id, $jumlah);
    if ($stmt->execute()) {
        $message = "✅ Pesanan berhasil dibuat!";
    } else {
        $message = "❌ Gagal memproses pesanan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan <?= $menu['nama'] ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>🛒 Pesan: <?= $menu['nama'] ?></h1>
    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="nama" required placeholder="Nama pelanggan">
        <input type="number" name="jumlah" required placeholder="Jumlah">
        <button type="submit">Pesan Sekarang</button>
    </form>
    <a href="index.php" class="btn">← Kembali</a>
</body>
</html>