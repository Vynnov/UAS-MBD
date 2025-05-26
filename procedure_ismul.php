<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sistem Peminjaman Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  
<body class="bg-gray-900 text-white font-sans">
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

<!-- Header -->
<section class="text-center mt-12">
  <h1 class="text-3xl font-bold">Riwayat Peminjaman</h1>
  <p class="text-gray-400 mt-2">Lihat Riwayat Peminjaman Ruangan Berdasarkan Nama Mahasiswa.</p>
</section>

<!-- Table Section -->
<section class="flex justify-center mt-10 px-4">
  <div class="w-full max-w-4xl bg-gray-800 p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Riwayat Peminjaman Berdasarkan Nama</h2>
    <form method="POST" action="" class="mb-6">
      <input type="text" name="nama" placeholder="Masukkan Nama Mahasiswa" class="w-full p-2 rounded bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
      <button type="submit" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Cari</button>
    </form>
</section>
<section class="flex justify-center mt-10 px-4">
  <div class="w-full max-w-4xl bg-gray-800 p-10 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Hasil Pencarian</h2>
    <div class="overflow-x-auto">
      <?php
      session_start();
        include 'koneksi.php';
        
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nama"])) {
          $nama_mahasiswa = $_POST["nama"];
          
          $stmt = $conn->prepare("CALL RiwayatBerdasarkanNama(?)");
          $stmt->bind_param("s", $nama_mahasiswa);
          $stmt->execute();
          
          $result = $stmt->get_result();
          
          echo "<h3 class='text-lg font-semibold mb-4'>" . htmlspecialchars($nama_mahasiswa) . "</h3>";
          
          if ($result->num_rows > 0) {
            echo "<div class='overflow-x-auto'>";
            echo "<table class='table-fixed w-full bg-gray-800 text-white rounded-lg'>";
            echo "<thead class='text-gray-300 border-b border-gray-600'>
            <tr>
            <th class='w-1/3 px-4 py-2 text-left'>Tanggal Konfirmasi</th>
            <th class='w-1/3 px-4 py-2 text-left'>Kondisi Ruangan</th>
            <th class='w-1/3 px-4 py-2 text-left'>Keterangan</th>
            </tr>
            </thead>";
            echo "<tbody>";
            
            while ($row = $result->fetch_assoc()) {
              echo "<tr class='border-t border-gray-700 hover:bg-gray-700'>";
              echo "<td class='px-4 py-2'>" . htmlspecialchars($row["tgl_konfirmasi"]) . "</td>";
              echo "<td class='px-4 py-2'>" . htmlspecialchars($row["kondisi_ruangan"]) . "</td>";
              echo "<td class='px-4 py-2'>" . htmlspecialchars($row["keterangan"]) . "</td>";
              echo "</tr>";
            }
            
            echo "</tbody></table></div>";
          } else {
            echo "<p class='text-gray-400'>Tidak ada riwayat peminjaman ditemukan.</p>";
          }
          
        }
      ?>
  </div>
</section>      
</body>
</html>       