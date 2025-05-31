<?php
include 'koneksi.php';
$keyword = $_GET['search'] ?? '';
$stmt = $conn->prepare("SELECT * FROM menu WHERE nama LIKE ? ORDER BY id DESC");
$searchTerm = "%$keyword%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Menu Makanan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>🍽️ Daftar Menu Makanan</h1>
    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Cari menu..." value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit">🔍 Cari</button>
    </form>
    <a href="tambah_menu.php" class="btn">➕ Tambah Menu</a>
    <div class="menu-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="menu-item">
                <img src="uploads/<?= $row['gambar'] ?>" alt="<?= $row['nama'] ?>">
                <h3><?= $row['nama'] ?></h3>
                <p><?= $row['deskripsi'] ?></p>
                <p class="harga">Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
                <a href="edit_menu.php?id=<?= $row['id'] ?>" class="btn">✏️ Edit</a>
                <a href="hapus_menu.php?id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Yakin hapus menu ini?')">❌ Hapus</a>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
