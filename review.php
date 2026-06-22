<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}

require_once "config/koneksi.php";

$id = (int)$_GET['id'];

$data = mysqli_query($conn,"
    SELECT *
    FROM riwayat
    WHERE id = $id
");

$r = mysqli_fetch_assoc($data);

if(!$r){
    die("Data tidak ditemukan");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Review Peminjaman</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">

    <h2>Review Peminjaman</h2>

    <p><b>Nama :</b> <?= $r['nama_peminjam']; ?></p>

    <p><b>Kendaraan :</b> <?= $r['kendaraan']; ?></p>

    <p><b>Pengemudi :</b> <?= $r['pengemudi']; ?></p>

    <p><b>Keperluan :</b> <?= $r['keperluan']; ?></p>

    <p><b>Tujuan :</b> <?= $r['tujuan']; ?></p>

    <br>

    <a
        href="proses/update_status.php?id=<?= $r['id']; ?>&status=disetujui"
        class="btn-1"
        style="background:#22c55e;"
    >
        Setujui
    </a>

    <a
        href="proses/update_status.php?id=<?= $r['id']; ?>&status=ditolak"
        class="btn-1"
        style="background:#ef4444;"
    >
        Tolak
    </a>

</div>

</body>
</html>