<?php
$data = json_decode(file_get_contents("php://input"), true);

$conn = new mysqli("localhost", "root", "", "peminjaman_db");

// Asumsi kita punya ID unik atau dapatkan kode_peminjaman
$nama = $data['nama'];
$ket = $data['keterangan'];
$mulai = $data['mulai'];
$selesai = $data['selesai'];
$id = $data['id']; // atau bisa juga kirim kode_peminjaman

// Contoh update berdasarkan waktu dan nama (kamu bisa sesuaikan)
$query = "
UPDATE peminjaman p
JOIN user u ON p.NIM = u.NIM
JOIN riwayat_peminjaman rp ON p.kode_peminjaman = rp.kode_peminjaman
SET 
    p.waktu_mulai = '$mulai',
    p.waktu_selesai = '$selesai',
    rp.keterangan = '$ket'
WHERE u.nama = '$nama'
LIMIT 1";

if ($conn->query($query)) {
  echo "Berhasil diupdate!";
} else {
  echo "Gagal update: " . $conn->error;
}
?>
