<?php
$conn = new mysqli("localhost", "root", "", "peminjaman_db");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$nim      = $_POST['nim'];
$nama     = $_POST['nama'];
$prodi    = $_POST['prodi'];
$no_hp    = $_POST['no_hp'];
$email    = $_POST['email'];
$password = $_POST['password'];

// Cek apakah NIM atau email sudah ada
$cek = $conn->query("SELECT * FROM user WHERE NIM='$nim' OR email='$email'");
if ($cek->num_rows > 0) {
    echo "<script>alert('NIM atau Email sudah terdaftar!'); window.location='register.html';</script>";
    exit();
}

// Insert ke database
$query = "INSERT INTO user (NIM, nama, prodi, no_hp, email, password)
          VALUES ('$nim', '$nama', '$prodi', '$no_hp', '$email', '$password')";

if ($conn->query($query) === TRUE) {
    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.html';</script>";
} else {
    echo "Gagal registrasi: " . $conn->error;
}

$conn->close();
?>
