<?php
require 'koneksi.php';

// Ambil peminjaman yang disetujui dan sudah selesai tapi belum masuk ke riwayat
$sql = "
SELECT p.kode_peminjaman
FROM peminjaman p
LEFT JOIN riwayat_peminjaman rp ON p.kode_peminjaman = rp.kode_peminjaman
WHERE p.status = 'Approved'
  AND p.waktu_selesai < NOW()
  AND rp.kode_peminjaman IS NULL
";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $kode = $row['kode_peminjaman'];

    // Masukkan ke riwayat dengan nilai default
    $conn->query("
      INSERT INTO riwayat_peminjaman (kode_peminjaman, tgl_konfirmasi, kondisi_ruangan, keterangan)
      VALUES ('$kode', NOW(), 'Baik', 'Selesai digunakan')
    ");
}
