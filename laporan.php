<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";

$queryLaporan = mysqli_query($conn,"
    SELECT
        r.*,
        k.merk_jenis
    FROM riwayat r
    LEFT JOIN kendaraan k
        ON r.kendaraan = k.no_polisi
    ORDER BY r.id DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>

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

            <li>
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

            <li class="active">
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

            <h1>Laporan Peminjaman</h1>

            <div class="profile">
                <a href="LOGIN.html">Admin</a>
            </div>

        </div>

        <!-- Cards -->
        <div class="cards">

            <div class="card">
                <h3>Total Peminjaman</h3>
                <p>120</p>
            </div>

            <div class="card">
                <h3>Kendaraan Aktif</h3>
                <p>18</p>
            </div>

            <div class="card">
                <h3>Total Pengemudi</h3>
                <p>10</p>
            </div>

            <div class="card">
                <h3>Laporan Bulan Ini</h3>
                <p>35</p>
            </div>

        </div>

        <!-- Filter -->
        <div class="form-container">

            <h2>Filter Laporan</h2>

            <div class="row">

                <div class="input-group">
                    <label>Tanggal Awal</label>
                    <input type="date" id="tglAwal">
                </div>

                <div class="input-group">
                    <label>Tanggal Akhir</label>
                    <input type="date" id="tglAkhir">
                </div>

                <div class="input-group">
                    <label>Status</label>
                    <select id="statusFilter">
                        <option>Semua</option>
                        <option>Disetujui</option>
                        <option>Pending</option>
                        <option>Ditolak</option>
                    </select>
                </div>

            </div>

            <button type="button" id="btnGenerate" class="btn-1">
                Generate Laporan
            </button>

        </div>

        <!-- Table -->
        <div class="table-container">

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:20px;
            ">

                <h2>Data Laporan</h2>

                <button id="btnExportExcel" style="
                    width:auto;
                    padding:12px 20px;
                " class="btn-1">
                    Export Excel
                </button>

            </div>

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Kendaraan</th>
                        <th>Pengemudi</th>
                        <th>Tanggal</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody id="dataLaporan">

                    <?php
                    $no = 1;
                    while ($r = mysqli_fetch_assoc($queryLaporan)) :
                    ?>

                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $r['nama_peminjam']; ?></td>
                        <td><?= $r['merk_jenis']; ?></td>
                        <td><?= $r['pengemudi']; ?></td>

                        <td>
                            <?= date('d F Y', strtotime($r['tanggal_pinjam'])); ?> 
                            -
                            <?= date('d F Y', strtotime($r['tanggal_kembali'])); ?>
                        </td>

                        <td><?= $r['tujuan']; ?></td>

                        <td>

                            <?php if($r['status'] == 'disetujui'): ?>

                                <span class="success">
                                    Disetujui
                                </span>

                            <?php elseif($r['status'] == 'pending'): ?>

                                <span class="pending">
                                    Pending
                                </span>

                            <?php else: ?>

                                <span style="
                                    background:#fee2e2;
                                    color:#991b1b;
                                    padding:8px 12px;
                                    border-radius:8px;
                                ">
                                    Ditolak
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script src="laporan.js"></script>
</body>
</html>