<?php

session_start();
include "../config/koneksi.php";

$id = intval($_POST['id_admin']);

// Ambil data akun yang akan dihapus
$query = mysqli_query(
    $conn,
    "SELECT * FROM admin
     WHERE id_admin='$id'"
);

$data = mysqli_fetch_assoc($query);

if(!$data)
{
    echo "<script>
        alert('Akun tidak ditemukan.');
        location='../pengaturan.php';
    </script>";
    exit;
}

// Mencegah akun yang sedang login menghapus dirinya sendiri
if($data['username'] == $_SESSION['admin'])
{
    echo "<script>
        alert('Akun yang sedang login tidak dapat dihapus.');
        location='../pengaturan.php';
    </script>";
    exit;
}

// Hapus akun
mysqli_query(
    $conn,
    "DELETE FROM admin
     WHERE id_admin='$id'"
);

echo "<script>
    alert('Akun berhasil dihapus.');
    location='../pengaturan.php';
</script>";