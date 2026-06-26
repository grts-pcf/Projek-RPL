<?php

require_once "../config/koneksi.php";

$id         = $_POST['id'];
$nama_supir = $_POST['nama_supir'];
$no_polisi  = $_POST['no_polisi'];

mysqli_query($conn,"
    UPDATE supir
    SET
        nama_supir = '$nama_supir',
        no_polisi = '$no_polisi'
    WHERE id = '$id'
");

header("Location: ../pengemudi.php");
exit();