<?php
require_once "../config/koneksi.php";

$id            = $_POST['id'];
$nama_peminjam = $_POST['nama_peminjam'];
$unit          = $_POST['unit'];
$no_telepon    = $_POST['no_telepon'];

mysqli_query($conn,"
    UPDATE data_peminjam
    SET
        nama_peminjam = '$nama_peminjam',
        unit = '$unit',
        no_telepon = '$no_telepon'
    WHERE id = '$id'
");

header("Location: ../data-peminjam.php");
exit();