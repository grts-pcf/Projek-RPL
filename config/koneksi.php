<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "db_peminjaman_kendaraan";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Mengatur charset agar mendukung karakter UTF-8
mysqli_set_charset($conn, "utf8");
?>