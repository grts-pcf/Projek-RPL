<?php

require_once "../config/koneksi.php";

$merk =
$_POST['merk_jenis'];

$tahun =
$_POST['tahun'];

$no_polisi =
$_POST['no_polisi'];

$tgl_pajak =
$_POST['tgl_pajak_stnk'];

$jenis =
$_POST['jenis'];

mysqli_query($conn,"
INSERT INTO kendaraan
(
    merk_jenis,
    tahun,
    no_polisi,
    tgl_pajak_stnk,
    jenis,
    status
)
VALUES
(
    '$merk',
    '$tahun',
    '$no_polisi',
    '$tgl_pajak',
    '$jenis',
    'siap'
)
");

header("Location: ../kendaraan.php");
exit();