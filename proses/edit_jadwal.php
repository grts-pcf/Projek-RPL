<?php
require_once "../config/koneksi.php";

$id = $_POST['id'];
$id_kendaraan = $_POST['id_kendaraan'];
$tanggal_service = $_POST['tanggal_service'];
$tanggal_spk = $_POST['tanggal_spk'];
$no_spk = $_POST['no_spk'];
$tanggal_lpj = $_POST['tanggal_lpj'];
$no_lpj = $_POST['no_lpj'];
$km = $_POST['km_ganti_oli'];
$km_next = $_POST['km_ganti_oli_selanjutnya'];
$keterangan = $_POST['keterangan'];

mysqli_query($conn,"
UPDATE jadwal_ganti_oli_kendaraan_operasional
SET
    id_kendaraan='$id_kendaraan',
    tanggal_service='$tanggal_service',
    tanggal_spk='$tanggal_spk',
    no_spk='$no_spk',
    tanggal_lpj='$tanggal_lpj',
    no_lpj='$no_lpj',
    km_ganti_oli='$km',
    km_ganti_oli_selanjutnya='$km_next',
    keterangan='$keterangan'
WHERE id='$id'
");

header("Location: ../jadwal.php");
exit;
?>