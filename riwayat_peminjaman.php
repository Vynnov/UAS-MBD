<?php
session_start();
require 'koneksi.php';

// Hanya boleh diakses jika sudah login
if (!isset($_SESSION['role'])) {
  header("Location: login.php");
  exit;
}

// Ambil data riwayat peminjaman dari database
$sql = "SELECT r.nama_ruangan, rp.tgl_konfirmasi, rp.kondisi_ruangan, rp.keterangan
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
  <title>Riwayat Peminjaman</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen">
  <!-- Navbar -->
  <nav class="flex items-center justify-between px-8 py-4 bg-gray-800 shadow">
    <div class="text-xl font-semibold">Sistem Peminjaman Ruangan</div>
    <ul class="flex space-x-6 text-sm">
      <li><a href="daftar_ruangan.php" class="hover:text-blue-400">Daftar Ruangan</a></li>
      <li><a href="riwayat_peminjaman.php" class="hover:text-blue-400">Riwayat Peminjaman</a></li>
      <!-- <li><a href="#" class="hover:text-blue-400 font-bold">Contact</a></li> -->
      <li><a href="logout.php" class="hover:text-blue-400">Logout</a></li>
    </ul>
  </nav>
  <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Riwayat Peminjaman Ruangan</h1>

    <div class="overflow-x-auto bg-gray-800 rounded-lg p-4 shadow">
      <table class="w-full table-auto">
        <thead class="text-left text-gray-300 border-b border-gray-600">
          <tr>
            <th class="py-2 px-3">No</th>
            <th class="py-2 px-3">Nama Ruangan</th>
            <th class="py-2 px-3">Tanggal Konfirmasi</th>
            <th class="py-2 px-3">Kondisi Ruangan</th>
            <th class="py-2 px-3">Keterangan</th>
          </tr>
        </thead>
        <tbody class="text-white">
          <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
            <tr class="border-b border-gray-700">
              <td class="py-2 px-3"><?= $no++ ?></td>
              <td class="py-2 px-3"><?= $row['nama_ruangan'] ?></td>
              <td class="py-2 px-3"><?= $row['tgl_konfirmasi'] ?></td>
              <td class="py-2 px-3"><?= $row['kondisi_ruangan'] ?></td>
              <td class="py-2 px-3"><?= $row['keterangan'] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
