<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Peminjaman</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
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
        <?php
$conn = new mysqli("localhost", "root", "", "peminjaman_db");
$no = 1;
$query = "
SELECT 
  r.nama_ruangan,
  u.nama AS nama_peminjam,
  rp.keterangan,
  p.waktu_mulai,
  p.waktu_selesai,
  rp.kondisi_ruangan
FROM peminjaman p
LEFT JOIN ruangan r ON p.kode_ruangan = r.kode_ruangan
LEFT JOIN user u ON p.NIM = u.NIM
LEFT JOIN riwayat_peminjaman rp ON rp.kode_peminjaman = p.kode_peminjaman
";

$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    echo "<tr class='border-b border-gray-700'>";
    echo "<td class='py-2 px-3'>{$no}</td>";
    echo "<td class='py-2 px-3'>{$row['nama_ruangan']}</td>";
    echo "<td class='py-2 px-3'>{$row['nama_peminjam']}</td>";
    echo "<td class='py-2 px-3'>{$row['keterangan']}</td>";
    echo "<td class='py-2 px-3'>{$row['waktu_mulai']}</td>";
    echo "<td class='py-2 px-3'>{$row['waktu_selesai']}</td>";
    echo "<td class='py-2 px-3'><span class='bg-red-500 px-2 py-1 rounded'>{$row['kondisi_ruangan']}</span></td>";
    echo "<td class='py-2 px-3'><button onclick='openModal(this)' class='bg-blue-300 text-black px-3 py-1 rounded'>Edit</button></td>";
    echo "</tr>";
    $no++;
}
$conn->close();
?>
          <tr class="border-b border-gray-700">
            <td class="py-2 px-3">1</td>
            <td class="py-2 px-3">Lab Komputer 1</td>
            <td class="py-2 px-3">Ismul Adjham</td>
            <td class="py-2 px-3">Digunakan untuk praktikum dasar pemrograman</td>
            <td class="py-2 px-3">2025-05-01 08:00</td>
            <td class="py-2 px-3">2025-05-01 10:00</td>
            <td class="py-2 px-3"><span class="bg-red-500 px-2 py-1 rounded">Used</span></td>
            <td class="py-2 px-3">
              <button onclick="openModal(this)" class="bg-blue-300 text-black px-3 py-1 rounded">Edit</button>
            </td>
          </tr>
          <!-- Tambahkan baris lain di sini jika perlu -->
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
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      nama: nama,
      keterangan: ket,
      mulai: mulai,
      selesai: selesai
      // Tambahkan kode_peminjaman jika tersedia
    })
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
