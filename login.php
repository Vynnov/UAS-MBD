<?php
session_start();
$conn = new mysqli("localhost", "root", "", "peminjaman_db");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

// Cek di tabel `user`
$query = "SELECT * FROM user WHERE email='$email' AND password='$password'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['nim'] = $user['NIM'];
    $_SESSION['nama'] = $user['nama'];

    header("Location: daftar_peminjaman.php");
    exit();
} else {
    echo "<script>alert('Login gagal! Email atau password salah.'); window.location.href='login.html';</script>";
}
?>
