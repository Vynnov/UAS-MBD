<!-- Procedure Kevin -->
<?php
require 'koneksi.php';

$hasil = [];
if (isset($_POST['cari'])) {
  $tanggal_awal = $_POST['tanggal_awal'];
  $tanggal_akhir = $_POST['tanggal_akhir'];

  $stmt = $conn->prepare("CALL CariRiwayatPeminjaman(?, ?)");
  $stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $hasil[] = $row;
  }
  $stmt->close();
}
?>
<!-- /Procedure -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sistem Peminjaman Ruangan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white h-screen overflow-hidden flex flex-col">

  <!-- Navbar -->
  <nav class="flex items-center justify-between px-8 py-4 bg-gray-800 shadow">
  <div class="text-xl font-semibold">Sistem Peminjaman Ruangan</div>
  <ul class="flex space-x-6 text-sm items-center">
    <li><a href="daftar_ruangan.php" class="hover:text-blue-400">Daftar Ruangan</a></li>
    <li><a href="riwayat_peminjaman.php" class="hover:text-blue-400">Riwayat Peminjaman</a></li>
    <li><a href="daftar_peminjaman.php" class="hover:text-blue-400">Daftar Peminjaman</a></li>
    
    <!-- Dropdown Procedure -->
    <li class="relative group">
      <button class="hover:text-blue-400 focus:outline-none">Procedure</button>
      <ul class="absolute z-10 hidden group-hover:block bg-gray-700 text-white rounded shadow mt-1 min-w-max">
        <li><a href="procedure_ismul.php" class="block px-4 py-2 hover:bg-gray-600">Ismul Adjham</a></li>
        <li><a href="procedure_nadim.php" class="block px-4 py-2 hover:bg-gray-600">Nadim Fadhilah</a></li>
        <li><a href="procedure_judith.php" class="block px-4 py-2 hover:bg-gray-600">Judithya Angeline</a></li>
        <li><a href="procedure_kevin.php" class="block px-4 py-2 hover:bg-gray-600">Kevin Novaldy</a></li>
      </ul>
    </li>

    <li><a href="logout.php" class="hover:text-blue-400">Logout</a></li>
  </ul>
</nav>
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Cari Riwayat Peminjaman</h1>

    <!-- Form Pencarian -->
    <form method="POST" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm mb-1">Tanggal Awal</label>
        <input type="date" name="tanggal_awal" class="w-full px-3 py-2 rounded bg-gray-700 text-white" required>
      </div>
      <div>
        <label class="block text-sm mb-1">Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" class="w-full px-3 py-2 rounded bg-gray-700 text-white" required>
      </div>
      <div class="flex items-end">
        <button type="submit" name="cari" class="bg-blue-500 px-4 py-2 rounded text-white font-semibold">Cari</button>
      </div>
    </form>

    <!-- Tabel Hasil -->
    <?php if (!empty($hasil)): ?>
    <div class="overflow-x-auto bg-gray-800 rounded-lg p-4 shadow">
      <table class="w-full table-auto">
        <thead class="text-left text-gray-300 border-b border-gray-600">
          <tr>
            <th class="py-2 px-3">Nama Mahasiswa</th>
            <th class="py-2 px-3">Nama Ruangan</th>
            <th class="py-2 px-3">Tanggal</th>
            <th class="py-2 px-3">Kondisi</th>
            <th class="py-2 px-3">Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($hasil as $row): ?>
            <tr class="border-b border-gray-700">
              <td class="py-2 px-3"><?= $row['nama_mahasiswa'] ?? $row['nama'] ?? '-' ?></td>
              <td class="py-2 px-3"><?= $row['nama_ruangan'] ?? '-' ?></td>
              <td class="py-2 px-3"><?= $row['tgl_konfirmasi'] ?? '-' ?></td>
              <td class="py-2 px-3"><?= $row['kondisi_ruangan'] ?? '-' ?></td>
              <td class="py-2 px-3"><?= $row['keterangan'] ?? '-' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php elseif (isset($_POST['cari'])): ?>
      <p class="text-red-400">Tidak ada data riwayat pada rentang tanggal tersebut.</p>
    <?php endif; ?>
  </div>
<!-- Procedure Ismul -->
<!-- Procedure Nadim -->
<!-- Procedure Judith -->
</body>
</html>