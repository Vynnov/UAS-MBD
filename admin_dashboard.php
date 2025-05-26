<?php
session_start();

// Pastikan hanya admin yang boleh akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit;
}

require 'koneksi.php';

// Ambil data peminjaman dari database
$sql = "SELECT 
          p.kode_peminjaman, 
          r.nama_ruangan, 
          p.NIM, 
          p.waktu_mulai, 
          p.waktu_selesai, 
          p.status,
          p.NIP,
          a.nama
        FROM peminjaman p
        JOIN ruangan r ON r.kode_ruangan = p.kode_ruangan
        LEFT JOIN administrator a ON p.NIP = a.NIP
        ORDER BY p.tanggal_peminjaman DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <!-- Navbar -->
  <nav class="flex items-center justify-between px-8 py-4 bg-gray-800 shadow">
    <div class="text-xl font-semibold">Sistem Peminjaman Ruangan</div>
    <ul class="flex space-x-6 text-sm">
      <li><a href="admin_dashboard.php" class="hover:text-blue-400">Home</a></li>
      <li><a href="riwayat_peminjamanAdmin.php" class="hover:text-blue-400">Riwayat Peminjaman</a></li>
      <!-- <li><a href="#" class="hover:text-blue-400 font-bold">Contact</a></li> -->
      <li><a href="logout_admin.php" class="hover:text-blue-400">Logout</a></li>
    </ul>
  </nav>
  <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>
    <div class="overflow-x-auto">
      <table class="w-full bg-gray-800 rounded-lg overflow-hidden">
        <thead class="bg-gray-700">
          <tr>
            <th class="px-4 py-2 text-left">Kode</th>
            <th class="px-4 py-2 text-left">Ruangan</th>
            <th class="px-4 py-2 text-left">NIM</th>
            <th class="px-4 py-2 text-left">Mulai</th>
            <th class="px-4 py-2 text-left">Selesai</th>
            <th class="px-4 py-2 text-left">Status</th>
            <th class="px-4 py-2 text-left">Aksi</th>
            <th class="px-4 py-2 text-left">Nama Admin</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="border-b border-gray-700">
              <td class="px-4 py-2"><?php echo $row['kode_peminjaman']; ?></td>
              <td class="px-4 py-2"><?php echo $row['nama_ruangan']; ?></td>
              <td class="px-4 py-2"><?php echo $row['NIM']; ?></td>
              <td class="px-4 py-2"><?php echo $row['waktu_mulai']; ?></td>
              <td class="px-4 py-2"><?php echo $row['waktu_selesai']; ?></td>
              <td class="px-4 py-2"><?php echo isset($row['status']) ? $row['status'] : 'Belum ada'; ?></td>
              <td class="px-4 py-2">
              <?php if (isset($row['status']) && $row['status'] === 'Pending'): ?>
                  <form action="verifikasi_peminjaman.php" method="POST" class="flex gap-2">
                    <input type="hidden" name="kode" value="<?php echo $row['kode_peminjaman']; ?>">
                    <button name="status" value="Approved" class="bg-green-500 px-3 py-1 rounded text-sm">Setujui</button>
                    <button name="status" value="Rejected" class="bg-red-500 px-3 py-1 rounded text-sm">Tolak</button>
                  </form>
                <?php else: ?>
                  <span class="text-sm italic text-gray-400">Sudah diverifikasi</span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-2">
              <?= $row['nama'] ?? '<i>Belum diverifikasi</i>' ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
