<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "peminjaman_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$result = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim_awal = $_POST["nim_awal"];
    $nim_akhir = $_POST["nim_akhir"];
    $sql = "CALL LihatDetailUser('$nim_awal', '$nim_akhir')";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Mahasiswa Berdasarkan NIM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen">

<!-- Navbar -->
<nav class="flex items-center justify-between px-8 py-4 bg-gray-800 shadow">
  <div class="text-xl font-semibold">Sistem Peminjaman Ruangan</div>
  <ul class="flex space-x-6 text-sm items-center">
    <li><a href="daftar_ruangan.php" class="hover:text-blue-400">Daftar Ruangan</a></li>
    <li><a href="riwayat_peminjaman.php" class="hover:text-blue-400">Riwayat Peminjaman</a></li>
    <li><a href="daftar_peminjaman.php" class="hover:text-blue-400">Daftar Peminjaman</a></li>
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

<!-- Main Content -->
<div class="max-w-5xl mx-auto p-6">
  <h1 class="text-2xl font-bold mb-6">Cari Data Mahasiswa Berdasarkan NIM</h1>

  <!-- Form Input -->
  <form method="POST" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div>
      <label class="block text-sm mb-1">NIM Awal</label>
      <input type="text" name="nim_awal" placeholder="Contoh: D1041211001" class="w-full px-3 py-2 rounded bg-gray-700 text-white" required>
    </div>
    <div>
      <label class="block text-sm mb-1">NIM Akhir</label>
      <input type="text" name="nim_akhir" placeholder="Contoh: D1041211009" class="w-full px-3 py-2 rounded bg-gray-700 text-white" required>
    </div>
    <div class="flex items-end">
      <button type="submit" class="bg-blue-500 px-4 py-2 rounded text-white font-semibold hover:bg-blue-600">Lihat Data</button>
    </div>
  </form>

  <!-- Tabel Hasil -->
  <?php if ($result): ?>
  <div class="overflow-x-auto bg-gray-800 rounded-lg p-4 shadow">
    <table class="w-full table-auto">
      <thead class="text-left text-gray-300 border-b border-gray-600">
        <tr>
          <th class="py-2 px-3">NIM</th>
          <th class="py-2 px-3">Nama</th>
          <th class="py-2 px-3">Prodi</th>
          <th class="py-2 px-3">No HP</th>
          <th class="py-2 px-3">Email</th>
          <th class="py-2 px-3">Password</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr class="border-b border-gray-700">
              <td class="py-2 px-3"><?= $row["NIM"] ?></td>
              <td class="py-2 px-3"><?= $row["nama"] ?></td>
              <td class="py-2 px-3"><?= $row["prodi"] ?></td>
              <td class="py-2 px-3"><?= $row["no_hp"] ?></td>
              <td class="py-2 px-3"><?= $row["email"] ?></td>
              <td class="py-2 px-3"><?= $row["password"] ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6" class="py-2 px-3 text-center text-red-400">Tidak ada data ditemukan.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>