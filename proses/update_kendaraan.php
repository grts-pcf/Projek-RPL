<?php

require_once "../config/koneksi.php";

$id = $_POST['id_kendaraan'];

$merk = $_POST['merk_jenis'];
$tahun = $_POST['tahun'];
$polisi = $_POST['no_polisi'];
$pajak = $_POST['tanggal_pajak_stnk'];
$jenis = $_POST['jenis'];

mysqli_query($conn,"
UPDATE kendaraan
SET
    merk_jenis = '$merk',
    tahun = '$tahun',
    no_polisi = '$polisi',
    tanggal_pajak_stnk = '$pajak',
    jenis = '$jenis'
WHERE id_kendaraan = '$id'
");

header("Location: ../kendaraan.php");
exit();