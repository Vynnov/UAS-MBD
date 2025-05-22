<?php
session_start();
require 'koneksi.php';

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  http_response_code(403);
  echo "Akses ditolak.";
  exit;
}

// Pastikan data dikirim dengan benar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $kode = $_POST['kode'] ?? null;
  $status = $_POST['status'] ?? null;

  if (!$kode || !in_array($status, ['Approved', 'Rejected'])) {
    echo "Data tidak valid.";
    exit;
  }

  // Update status peminjaman
  $stmt = $conn->prepare("UPDATE peminjaman SET status = ? WHERE kode_peminjaman = ?");
  $stmt->bind_param("ss", $status, $kode);

  if ($stmt->execute()) {
    header("Location: admin_dashboard.php");
    exit;
  } else {
    echo "Gagal memperbarui status.";
  }
} else {
  echo "Metode tidak diperbolehkan.";
  http_response_code(405);
}
?>
