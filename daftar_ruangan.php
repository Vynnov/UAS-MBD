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
    <ul class="flex space-x-6 text-sm">
      <li><a href="#" class="hover:text-blue-400">Home</a></li>
      <li><a href="#" class="hover:text-blue-400">About Us</a></li>
      <li><a href="#" class="hover:text-blue-400">Contact</a></li>
      <li><a href="#" class="hover:text-blue-400">Login</a></li>
    </ul>
  </nav>

  <!-- Table Section -->
  <section class="flex justify-center mt-10 px-4">
    <div class="bg-gray-800 rounded-xl border border-gray-600 p-4 w-full max-w-6xl">
      <table class="w-full text-sm text-left">
        <thead class="text-gray-300 border-b border-gray-600">
          <tr>
            <th class="px-4 py-2">No</th>
            <th class="px-4 py-2">Nama Ruangan</th>
            <th class="px-4 py-2">Gedung</th>
            <th class="px-4 py-2">Kapasitas</th>
            <th class="px-4 py-2">Fasilitas</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Action</th>
          </tr>
        </thead>
        <tbody id="roomTableBody" class="text-gray-100"></tbody>
      </table>
    </div>
  </section>

  <!-- Modal Form Peminjaman -->
  <div id="modalPeminjaman" class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50">
    <form id="formPeminjaman" class="bg-gray-800 p-6 rounded-lg w-[400px] space-y-4 text-sm" onsubmit="submitPeminjaman(event)">
      <h2 class="text-lg font-bold">Form Peminjaman</h2>
      <input type="hidden" id="kodeRuanganInput" />

      <div>
        <label class="block mb-1">Tanggal Peminjaman</label>
        <input type="date" id="tanggal" class="w-full px-3 py-2 rounded bg-gray-700 text-white" required>
      </div>

      <div>
        <label class="block mb-1">Waktu Mulai</label>
        <input type="time" id="waktuMulai" class="w-full px-3 py-2 rounded bg-gray-700 text-white" required>
      </div>

      <div>
        <label class="block mb-1">Waktu Selesai</label>
        <input type="time" id="waktuSelesai" class="w-full px-3 py-2 rounded bg-gray-700 text-white" required>
      </div>

      <div class="text-right">
        <button type="submit" class="bg-blue-400 px-4 py-2 rounded text-black font-semibold">Ajukan</button>
        <button type="button" onclick="tutupModal()" class="ml-2 text-gray-300">Batal</button>
      </div>
    </form>
  </div>

  <script>
    const rooms = <?php
    $conn = new mysqli("localhost", "root", "", "peminjaman_db");
    $data = [];
    $result = $conn->query("SELECT * FROM ruangan");
    while ($row = $result->fetch_assoc()) {
      $data[] = [
        "kode_ruangan" => $row['kode_ruangan'],
        "nama" => $row['nama_ruangan'],
        "gedung" => $row['gedung'],
        "kapasitas" => (int) $row['kapasitas'],
        "fasilitas" => $row['fasilitas'],
        "status" => $row['status']
      ];
    }
    $conn->close();
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    ?>;

    const tableBody = document.getElementById("roomTableBody");

    function renderTable(data) {
      tableBody.innerHTML = "";
      data.forEach((room, index) => {
        const statusColor = room.status === "Available" ? "green" : "red";
        const row = `
          <tr class="border-b border-gray-700">
            <td class="px-4 py-2">${index + 1}</td>
            <td class="px-4 py-2">${room.nama}</td>
            <td class="px-4 py-2">${room.gedung}</td>
            <td class="px-4 py-2">${room.kapasitas} Orang</td>
            <td class="px-4 py-2">${room.fasilitas}</td>
            <td class="px-4 py-2">
              <span class="bg-${statusColor}-800 text-${statusColor}-200 px-2 py-1 rounded-md text-xs">${room.status}</span>
            </td>
            <td class="px-4 py-2">
              ${room.status === "Available" ? `
                <button onclick="bukaModal('${room.kode_ruangan}')" class="bg-blue-200 text-black px-3 py-1 rounded-md flex items-center space-x-1">
                  <span>Pinjam Ruangan</span>
                </button>` : `<span class="text-gray-400 text-sm italic">Sudah Dipinjam</span>`}
            </td>
          </tr>`;
        tableBody.innerHTML += row;
      });
    }

    function bukaModal(kodeRuangan) {
      document.getElementById("kodeRuanganInput").value = kodeRuangan;
      document.getElementById("modalPeminjaman").classList.remove("hidden");
    }

    function tutupModal() {
      document.getElementById("modalPeminjaman").classList.add("hidden");
    }

    function submitPeminjaman(e) {
      e.preventDefault();
      const kode_ruangan = document.getElementById("kodeRuanganInput").value;
      const tanggal = document.getElementById("tanggal").value;
      const waktu_mulai = document.getElementById("waktuMulai").value;
      const waktu_selesai = document.getElementById("waktuSelesai").value;

      if (waktu_mulai < "06:00" || waktu_selesai > "17:00" || waktu_mulai >= waktu_selesai) {
        alert("Jam hanya boleh antara 06:00 - 17:00 dan jam mulai < jam selesai.");
        return;
      }

      fetch("peminjaman.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ kode_ruangan, tanggal, waktu_mulai, waktu_selesai })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("Peminjaman berhasil!");
          tutupModal();
          location.reload();
        } else {
          alert("Gagal: " + data.error);
        }
      })
      .catch(err => alert("Terjadi kesalahan: " + err));
    }

    renderTable(rooms);
  </script>
</body>
</html>
