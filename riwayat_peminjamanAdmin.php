<?php
session_start();
require 'koneksi.php';
include 'update_riwayat.php';

if (!isset($_SESSION['role'])) {
  header("Location: login.php");
  exit;
}

// Proses update kondisi_ruangan
if (isset($_POST['update'])) {
  $kode_peminjaman = $_POST['kode_peminjaman'];
  $tgl_konfirmasi = $_POST['tgl_konfirmasi'];
  $kondisi = $_POST['kondisi_ruangan'];

  $stmt = $conn->prepare("UPDATE riwayat_peminjaman SET kondisi_ruangan = ? WHERE kode_peminjaman = ? AND tgl_konfirmasi = ?");
  $stmt->bind_param("sss", $kondisi, $kode_peminjaman, $tgl_konfirmasi);
  $stmt->execute();
  $stmt->close();
}

// Ambil data riwayat peminjaman
$sql = "SELECT r.nama_ruangan, rp.tgl_konfirmasi, rp.kondisi_ruangan, rp.keterangan, rp.kode_peminjaman
        FROM riwayat_peminjaman rp
        JOIN peminjaman p ON p.kode_peminjaman = rp.kode_peminjaman
        JOIN ruangan r ON r.kode_ruangan = p.kode_ruangan
        ORDER BY rp.tgl_konfirmasi DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Peminjaman (Admin)</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen">

<!-- Navbar -->
<nav class="flex items-center justify-between px-8 py-4 bg-gray-800 shadow">
  <div class="text-xl font-semibold">Sistem Peminjaman Ruangan</div>
  <ul class="flex space-x-6 text-sm items-center">
    <li><a href="admin_dashboard.php" class="hover:text-blue-400">Home</a></li>
    <li><a href="riwayat_peminjamanAdmin.php" class="hover:text-blue-400">Riwayat Peminjaman</a></li>
    <!-- <li><a href="daftar_peminjaman.php" class="hover:text-blue-400">Daftar Peminjaman</a></li>
    <li class="relative group">
      <button class="hover:text-blue-400 focus:outline-none">Procedure</button>
      <ul class="absolute z-10 hidden group-hover:block bg-gray-700 text-white rounded shadow mt-1 min-w-max">
        <li><a href="procedure_ismul.php" class="block px-4 py-2 hover:bg-gray-600">Ismul Adjham</a></li>
        <li><a href="procedure_nadim.php" class="block px-4 py-2 hover:bg-gray-600">Nadim Fadhilah</a></li>
        <li><a href="procedure_judith.php" class="block px-4 py-2 hover:bg-gray-600">Judithya Angeline</a></li>
        <li><a href="procedure_kevin.php" class="block px-4 py-2 hover:bg-gray-600">Kevin Novaldy</a></li>
      </ul>
    </li> -->
    <li><a href="logout.php" class="hover:text-blue-400">Logout</a></li>
  </ul>
</nav>

<div class="max-w-6xl mx-auto p-6">
  <h1 class="text-2xl font-bold mb-6">Riwayat Peminjaman Ruangan (Admin)</h1>

  <div class="overflow-x-auto bg-gray-800 rounded-lg p-4 shadow">
    <table class="w-full table-auto">
      <thead class="text-left text-gray-300 border-b border-gray-600">
        <tr>
          <th class="py-2 px-3">No</th>
          <th class="py-2 px-3">Nama Ruangan</th>
          <th class="py-2 px-3">Tanggal Konfirmasi</th>
          <th class="py-2 px-3">Kondisi Ruangan</th>
          <th class="py-2 px-3">Keterangan</th>
          <th class="py-2 px-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-white">
        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
          <tr class="border-b border-gray-700">
            <form method="POST">
              <input type="hidden" name="kode_peminjaman" value="<?= $row['kode_peminjaman'] ?>">
              <input type="hidden" name="tgl_konfirmasi" value="<?= $row['tgl_konfirmasi'] ?>">
              <td class="py-2 px-3"><?= $no++ ?></td>
              <td class="py-2 px-3"><?= $row['nama_ruangan'] ?></td>
              <td class="py-2 px-3"><?= $row['tgl_konfirmasi'] ?></td>
              <td class="py-2 px-3">
                <input type="text" name="kondisi_ruangan" value="<?= htmlspecialchars($row['kondisi_ruangan']) ?>" class="bg-gray-700 px-2 py-1 rounded w-full">
              </td>
              <td class="py-2 px-3"><?= $row['keterangan'] ?></td>
              <td class="py-2 px-3">
                <button type="submit" name="update" class="bg-blue-500 px-3 py-1 rounded hover:bg-blue-600">Simpan</button>
              </td>
            </form>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
