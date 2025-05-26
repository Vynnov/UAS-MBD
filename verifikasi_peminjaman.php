<?php
session_start();
require 'koneksi.php';

// 🔒 Validasi: Hanya admin yang boleh akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  http_response_code(403);
  echo "Akses ditolak.";
  exit;
}

// ✅ Ambil NIP admin dari session (perbaikan di sini!)
$nip = $_SESSION['admin_nip'] ?? null;

// 🔎 Debug jika belum login admin
if (!$nip) {
  echo "Admin tidak dikenali. Pastikan Anda login sebagai admin.<br>";
  echo "<pre>";
  print_r($_SESSION);
  echo "</pre>";
  exit;
}

// ✅ Validasi method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Ambil data dari form
  $kode = $_POST['kode'] ?? null;
  $status = $_POST['status'] ?? null;

  // Validasi input
  if (!$kode || !in_array($status, ['Approved', 'Rejected'])) {
    echo "Data tidak valid.";
    exit;
  }

  // ✅ Proses update status + simpan NIP admin yang memverifikasi
  $stmt = $conn->prepare("UPDATE peminjaman SET status = ?, NIP = ? WHERE kode_peminjaman = ?");
  $stmt->bind_param("sss", $status, $nip, $kode);

  if ($stmt->execute()) {
    header("Location: admin_dashboard.php");
    exit;
  } else {
    echo "Gagal memperbarui status: " . $stmt->error;
  }

  $stmt->close();
} else {
  echo "Metode tidak diperbolehkan.";
  http_response_code(405);
}
?>