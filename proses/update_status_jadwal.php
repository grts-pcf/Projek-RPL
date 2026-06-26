<?php

require_once "../config/koneksi.php";

$id = $_POST['id'];
$status = $_POST['status'];

mysqli_query($conn,"
UPDATE jadwal_ganti_oli_kendaraan_operasional
SET status='$status'
WHERE id='$id'
");

header("Location: ../jadwal.php");
exit;