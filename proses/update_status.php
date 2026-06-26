<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: ../LOGIN.php");
    exit();
}

$id = (int)$_GET['id'];
$status = $_GET['status'];

if(
    $status != 'disetujui' &&
    $status != 'ditolak'
){
    die('Status tidak valid');
}

mysqli_query($conn,"
    UPDATE riwayat
    SET status = '$status'
    WHERE id = $id
");

header("Location: ../riwayat.php");
exit();