<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";

$query = mysqli_query($conn, "
    SELECT *
    FROM data_peminjam
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjam</title>

    <link rel="stylesheet" href="style.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">

        <div class="logo">

    <img 
        src="logo ubd.png" 
        alt="Logo UBD"
        class="logo-img"
    >

    <h2>Transportasi UBD</h2>

</div>

        <ul class="menu">

            <li>
                <a href="index.php">Dashboard</a>
            </li>

            <li class="active">
                <a href="data-peminjam.php">Data Peminjam</a>
            </li>

            <li>
                <a href="riwayat.php">Riwayat Peminjaman</a>
            </li>

            <li>
                <a href="kendaraan.php">Master Kendaraan</a>
            </li>

            <li>
                <a href="pengemudi.php">Pengemudi</a>
            </li>

            <li>
                <a href="laporan.php">Laporan</a>
            </li>

            <li>
                <a href="pengaturan.php">Pengaturan</a>
            </li>

            <li class="logout-menu">
                <a href="proses/logout.php">Logout</a>
            </li>

        </ul>

    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Navbar -->
        <div class="navbar">

            <h1>Data Peminjam</h1>

            <div class="profile">
                <a href="LOGIN.html">Admin</a>
            </div>

        </div>

        <!-- Header Action -->
        <div class="table-container">

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:20px;
            ">

                <h2>Daftar Data Peminjam</h2>

                <button id="btnTambah" style="
                    width:auto;
                    padding:12px 20px;
                " class="btn-1">
                    + Tambah Data
                </button>

            </div>

            <!-- Search -->
            <div class="input-group">

                <input
                type="text"
                id="searchInput"
                placeholder="Cari nama peminjam..."
>

            </div>

            <!-- Table -->
            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Unit</th>
                        <th>No Telepon</th>
                        <th>Riwayat Terakhir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody id="dataPeminjam">

                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query)) :
                    ?>

                    <?php

                    $nama = $row['nama_peminjam'];

                    $qRiwayat = mysqli_query($conn,"
                        SELECT tanggal_kembali, jam_kembali
                        FROM riwayat
                        WHERE nama_peminjam = '$nama'
                        ORDER BY id DESC
                        LIMIT 1
                    ");

                    $riwayat = mysqli_fetch_assoc($qRiwayat);

                    if($riwayat){

                        $batas_kembali = strtotime(
                            $riwayat['tanggal_kembali'] . ' ' .
                            $riwayat['jam_kembali']
                        );

                        $status = (time() <= $batas_kembali)
                            ? 'aktif'
                            : 'nonaktif';

                    }else{

                        $status = 'nonaktif';

                    }
                    ?>

                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nama_peminjam']; ?></td>
                        <td><?= $row['unit']; ?></td>
                        <td><?= $row['no_telepon']; ?></td>

                        <td>
                            <?php if ($riwayat) : ?>
                                <?= date('d F Y', strtotime($riwayat['tanggal_kembali'])) ?>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($status == 'aktif') : ?>
                                <span class="success">
                                    Aktif
                                </span>
                            <?php else : ?>
                                <span class="pending">
                                    Nonaktif
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="action-buttons">

                                <button
                                    class="btn-1 btnEdit"
                                    style="width:auto;padding:8px 15px;font-size:14px;">
                                    Edit
                                </button>

                                <button
                                    class="btn-1 btnHapus"
                                    style="width:auto;padding:8px 15px;font-size:14px;background:#ef4444;">
                                    Hapus
                                </button>

                            </div>
                        </td>
                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
<script src="script.js"></script>
</body>
</html>