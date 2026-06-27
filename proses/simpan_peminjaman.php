<?php
session_start();
require_once "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ==========================
    // AMBIL DATA FORM
    // ==========================
    $nama_peminjam   = mysqli_real_escape_string($conn, $_POST['nama_peminjam']);
    $unit            = mysqli_real_escape_string($conn, $_POST['unit']);
    $no_telepon      = mysqli_real_escape_string($conn, $_POST['no_telepon']);

    $tanggal_pinjam  = $_POST['tanggal_pinjam'];
    $jam_berangkat   = $_POST['jam_berangkat'];

    $tanggal_kembali = $_POST['tanggal_kembali'];
    $jam_kembali     = $_POST['jam_kembali'];

    $keperluan       = mysqli_real_escape_string($conn, $_POST['keperluan']);
    $tujuan          = mysqli_real_escape_string($conn, $_POST['tujuan']);

    $kendaraan       = mysqli_real_escape_string($conn, $_POST['kendaraan']);
    $pengemudi       = mysqli_real_escape_string($conn, $_POST['pengemudi']);

    // ==========================
    // STATUS PEMINJAMAN
    // ==========================
    $status = "pending";

    // ==========================
    // Upload PDF
    // ==========================

    $namaPdf = "";

    if(isset($_FILES['surat_pdf']) && $_FILES['surat_pdf']['error'] == 0)
    {
        $folder = "../uploads/pdf/";

        $ext = strtolower(pathinfo(
            $_FILES['surat_pdf']['name'],
            PATHINFO_EXTENSION
        ));

        if($ext != "pdf")
        {
            die("File harus berupa PDF.");
        }

        $namaPdf =
            time() . "_" .
            basename($_FILES['surat_pdf']['name']);

        if(!move_uploaded_file(
            $_FILES['surat_pdf']['tmp_name'],
            $folder . $namaPdf
        ))
        {
            die("Upload PDF gagal.");
        }
    }

    // ==========================
    // CEK DATA PEMINJAM
    // ==========================
    $cek = mysqli_query($conn, "
        SELECT id
        FROM data_peminjam
        WHERE
            nama_peminjam = '$nama_peminjam'
            AND unit = '$unit'
            AND no_telepon = '$no_telepon'
        LIMIT 1
    ");

    if (mysqli_num_rows($cek) == 0) {

        mysqli_query($conn, "
            INSERT INTO data_peminjam
            (
                nama_peminjam,
                unit,
                no_telepon
            )
            VALUES
            (
                '$nama_peminjam',
                '$unit',
                '$no_telepon'
            )
        ");

    }

    // ==========================
    // SIMPAN KE RIWAYAT
    // ==========================
    $simpan = mysqli_query($conn, "
        INSERT INTO riwayat
        (
            nama_peminjam,
            kendaraan,
            pengemudi,
            tanggal_pinjam,
            jam_berangkat,
            tanggal_kembali,
            jam_kembali,
            keperluan,
            tujuan,
            status,
            file_pdf
        )
        VALUES
        (
            '$nama_peminjam',
            '$kendaraan',
            '$pengemudi',
            '$tanggal_pinjam',
            '$jam_berangkat',
            '$tanggal_kembali',
            '$jam_kembali',
            '$keperluan',
            '$tujuan',
            '$status',
            '$namaPdf'
        )
    ");

    if ($simpan) {

        header("Location: ../index.php?success=1");
        exit();

    } else {

        echo "Gagal menyimpan data : " . mysqli_error($conn);

    }

} else {

    header("Location: ../index.php");
    exit();

}
?>