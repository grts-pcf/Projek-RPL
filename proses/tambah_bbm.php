<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../LOGIN.php");
    exit();
}

require_once "../config/koneksi.php";

if(isset($_POST['id_kendaraan'])){

    $id_kendaraan = mysqli_real_escape_string($conn,$_POST['id_kendaraan']);
    $id_supir = mysqli_real_escape_string($conn,$_POST['id_supir']);
    $tanggal_pengisian = mysqli_real_escape_string($conn,$_POST['tanggal_pengisian']);
    $km_sebelum = mysqli_real_escape_string($conn,$_POST['km_sebelum']);
    $km_sesudah = mysqli_real_escape_string($conn,$_POST['km_sesudah']);

    $jumlah_pengisian = mysqli_real_escape_string($conn,$_POST['jumlah_pengisian']);

    $km_terpakai = $km_sesudah - $km_sebelum;

    if ($km_sesudah <= $km_sebelum) {
        echo "<script>
                alert('KM sesudah harus lebih besar dari KM sebelum.');
                window.history.back();
            </script>";
        exit();
    }

    mysqli_query($conn,"
        INSERT INTO pengisian_bbm
        (
            id_kendaraan,
            id_supir,
            tanggal_pengisian,
            km_sebelum,
            km_sesudah,
            km_terpakai,
            jumlah_pengisian
        )
        VALUES
        (
            '$id_kendaraan',
            '$id_supir',
            '$tanggal_pengisian',
            '$km_sebelum',
            '$km_sesudah',
            '$km_terpakai',
            '$jumlah_pengisian'
        )
    ");

}

header("Location: ../jadwal.php");
exit();