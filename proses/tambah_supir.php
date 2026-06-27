<?php

require_once "../config/koneksi.php";

if(isset($_POST['simpan'])){

    $nama_supir = mysqli_real_escape_string(
        $conn,
        $_POST['nama_supir']
    );

    $no_polisi = mysqli_real_escape_string(
        $conn,
        $_POST['no_polisi']
    );

    $cek = mysqli_query(
    $conn,
    "SELECT no_polisi
     FROM kendaraan
     WHERE no_polisi='$no_polisi'"
);

if(mysqli_num_rows($cek) == 0){

    die("Nomor polisi tidak ditemukan pada tabel kendaraan.");

}

    mysqli_query(
        $conn,
        "INSERT INTO supir
        (nama_supir, no_polisi)
        VALUES
        ('$nama_supir', '$no_polisi')"
    );

    header("Location: ../pengemudi.php");
    exit();
}