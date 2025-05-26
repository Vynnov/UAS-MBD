<?php
$conn = new mysqli("localhost", "root", "", "peminjaman_db");
$no = 1;
$query = "
SELECT 
  p.kode_peminjaman,
  r.nama_ruangan,
  u.nama AS nama_peminjam,
  rp.keterangan,
  p.waktu_mulai,
  p.waktu_selesai,
  CASE 
    WHEN p.status = 'Approved' AND NOW() BETWEEN p.waktu_mulai AND p.waktu_selesai THEN 'Used'
    WHEN p.status = 'Approved' AND NOW() > p.waktu_selesai THEN 'Selesai'
    ELSE p.status
  END AS status
FROM peminjaman p
LEFT JOIN ruangan r ON p.kode_ruangan = r.kode_ruangan
LEFT JOIN user u ON p.NIM = u.NIM
LEFT JOIN riwayat_peminjaman rp ON rp.kode_peminjaman = p.kode_peminjaman
ORDER BY p.waktu_mulai DESC
";
include 'update_riwayat.php';
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Peminjaman</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
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

  <div class="p-6">
    <h1 class="text-3xl font-bold text-center mb-4">Daftar Peminjaman</h1>

    <div class="overflow-x-auto bg-gray-800 rounded-lg p-4">
      <table class="min-w-full table-auto">
        <thead>
          <tr class="text-left border-b border-gray-700">
            <th class="py-2 px-3">No</th>
            <th class="py-2 px-3">Nama Ruangan</th>
            <th class="py-2 px-3">Nama Peminjam</th>
            <th class="py-2 px-3">Keterangan</th>
            <th class="py-2 px-3">Waktu Mulai</th>
            <th class="py-2 px-3">Waktu Selesai</th>
            <th class="py-2 px-3">Status</th>
            <th class="py-2 px-3">Action</th>
          </tr>
        </thead>
        <tbody id="tableBody">
<?php while ($row = $result->fetch_assoc()) : ?>
  <tr class='border-b border-gray-700'>
    <td class='py-2 px-3'><?= $no++ ?></td>
    <td class='py-2 px-3'><?= $row['nama_ruangan'] ?></td>
    <td class='py-2 px-3'><?= $row['nama_peminjam'] ?></td>
    <td class='py-2 px-3'><?= $row['keterangan'] ?></td>
    <td class='py-2 px-3'><?= $row['waktu_mulai'] ?></td>
    <td class='py-2 px-3'><?= $row['waktu_selesai'] ?></td>
    <td class='py-2 px-3'>
      <span class='px-2 py-1 rounded
        <?= $row['status'] == 'Used' ? 'bg-red-500' : ($row['status'] == 'Selesai' ? 'bg-green-500' : 'bg-yellow-500') ?>'>
        <?= $row['status'] ?>
      </span>
    </td>
    <td class='py-2 px-3'>
      <button onclick='openModal(this)' 
              data-kode="<?= $row['kode_peminjaman'] ?>"
              class='bg-blue-300 text-black px-3 py-1 rounded'>Edit</button>
    </td>
  </tr>
<?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 hidden">
    <div class="bg-gray-800 p-6 rounded-lg w-[600px]">
      <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Edit Peminjaman</h2>
        <button onclick="closeModal()" class="text-red-400 font-bold text-xl">×</button>
      </div>
      <form id="editForm" onsubmit="saveChanges(event)">
        <input type="hidden" id="kodePeminjamanInput">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Nama Peminjam</label>
            <input id="namaInput" class="w-full px-3 py-2 rounded bg-gray-700 text-white" />
          </div>
          <div>
            <label class="block text-sm mb-1">Keterangan</label>
            <input id="keteranganInput" class="w-full px-3 py-2 rounded bg-gray-700 text-white" />
          </div>
          <div>
            <label class="block text-sm mb-1">Waktu Mulai</label>
            <input id="mulaiInput" type="datetime-local" class="w-full px-3 py-2 rounded bg-gray-700 text-white" />
          </div>
          <div>
            <label class="block text-sm mb-1">Waktu Selesai</label>
            <input id="selesaiInput" type="datetime-local" class="w-full px-3 py-2 rounded bg-gray-700 text-white" />
          </div>
        </div>
        <div class="text-right mt-6">
          <button type="submit" class="bg-blue-400 text-black px-4 py-2 rounded font-semibold">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    let currentRow = null;

    function openModal(button) {
      currentRow = button.closest("tr");
      const cells = currentRow.querySelectorAll("td");

      document.getElementById("kodePeminjamanInput").value = button.getAttribute("data-kode");
      document.getElementById("namaInput").value = cells[2].textContent.trim();
      document.getElementById("keteranganInput").value = cells[3].textContent.trim();
      document.getElementById("mulaiInput").value = cells[4].textContent.trim().replace(" ", "T");
      document.getElementById("selesaiInput").value = cells[5].textContent.trim().replace(" ", "T");

      document.getElementById("editModal").classList.remove("hidden");
    }

    function closeModal() {
      document.getElementById("editModal").classList.add("hidden");
    }

    function saveChanges(e) {
      e.preventDefault();

      const kode = document.getElementById("kodePeminjamanInput").value;
      const nama = document.getElementById("namaInput").value;
      const ket = document.getElementById("keteranganInput").value;
      const mulai = document.getElementById("mulaiInput").value.replace("T", " ");
      const selesai = document.getElementById("selesaiInput").value.replace("T", " ");

      const cells = currentRow.querySelectorAll("td");
      cells[2].textContent = nama;
      cells[3].textContent = ket;
      cells[4].textContent = mulai;
      cells[5].textContent = selesai;

      fetch("update_peminjaman.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ kode, nama, keterangan: ket, mulai, selesai })
      })
      .then(res => res.text())
      .then(msg => {
        console.log(msg);
        alert("Data berhasil diperbarui!");
      })
      .catch(err => {
        console.error("Gagal:", err);
        alert("Terjadi kesalahan.");
      });

      closeModal();
    }
  </script>
</body>
</html>
