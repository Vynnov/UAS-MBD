<?php
session_start();
require 'koneksi.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Cek di tabel user/mahasiswa
  $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND password = ?");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $_SESSION['role'] = 'user';
    $_SESSION['nim'] = $user['NIM'];
    $_SESSION['nama'] = $user['nama'];
    header("Location: daftar_ruangan.php");
    exit;
  }

  // Jika tidak ditemukan, cek di administrator
  $stmt = $conn->prepare("SELECT * FROM administrator WHERE email = ? AND password = ?");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    $_SESSION['role'] = 'admin';
    $_SESSION['admin_nip'] = $admin['NIP'];
    $_SESSION['admin_nama'] = $admin['nama'];
    header("Location: admin_dashboard.php");
    exit;
  }

  $error = "Email atau password salah!";
}
?>
