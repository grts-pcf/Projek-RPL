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