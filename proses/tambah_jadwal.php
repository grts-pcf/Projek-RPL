<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../LOGIN.php");
    exit();
}

require_once "../config/koneksi.php";

if(isset($_POST['id_kendaraan'])){

    $id_kendaraan = mysqli_real_escape_string($conn,$_POST['id_kendaraan']);
    $tanggal_service = mysqli_real_escape_string($conn,$_POST['tanggal_service']);
    $tanggal_spk = $_POST['tanggal_spk'];
    $no_spk = $_POST['no_spk'];
    $tanggal_lpj = $_POST['tanggal_lpj'];
    $no_lpj = $_POST['no_lpj'];
    $km_ganti_oli = mysqli_real_escape_string($conn,$_POST['km_ganti_oli']);
    $km_ganti_oli_selanjutnya = mysqli_real_escape_string($conn,$_POST['km_ganti_oli_selanjutnya']);
    $keterangan = $_POST['keterangan'];
    
    // ambil no polisi dari tabel kendaraan
    $kendaraan = mysqli_query($conn,"
        SELECT no_polisi
        FROM kendaraan
        WHERE id_kendaraan='$id_kendaraan'
    ");

    $data = mysqli_fetch_assoc($kendaraan);

    $no_polisi = $data['no_polisi'];

    mysqli_query($conn,"
        INSERT INTO jadwal_ganti_oli_kendaraan_operasional
        (
            id_kendaraan,
            no_polisi,
            tanggal_service,
            tanggal_spk,
            no_spk,
            tanggal_lpj,
            no_lpj,
            km_ganti_oli,
            km_ganti_oli_selanjutnya,
            keterangan
        )
        VALUES
        (
            '$id_kendaraan',
            '$no_polisi',
            '$tanggal_service',
            '$tanggal_spk',
            '$no_spk',
            '$tanggal_lpj',
            '$no_lpj',
            '$km_ganti_oli',
            '$km_ganti_oli_selanjutnya',
            '$keterangan'
        )
    ");

}

header("Location: ../jadwal.php");
exit();