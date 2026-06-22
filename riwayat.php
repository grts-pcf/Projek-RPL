<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";

$queryRiwayat = mysqli_query($conn,"
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
    <title>Riwayat Peminjaman</title>

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

            <li class="active">
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

            <h1>Riwayat Peminjaman</h1>

            <div class="profile">
                <a href="LOGIN.html">Admin</a>
            </div>

        </div>

        <!-- Statistik -->
        <div class="cards">

            <div class="card">
                <h3>Total Peminjaman</h3>
                <p>120</p>
            </div>

            <div class="card">
                <h3>Disetujui</h3>
                <p>95</p>
            </div>

            <div class="card">
                <h3>Pending</h3>
                <p>15</p>
            </div>

            <div class="card">
                <h3>Ditolak</h3>
                <p>10</p>
            </div>

        </div>

        <!-- Table -->
        <div class="table-container">

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:20px;
            ">

                <h2>Daftar Riwayat Peminjaman</h2>

            <button id="btnExport" style="
                width:auto;
                padding:12px 20px;
            " class="btn-1">
                Export PDF
            </button>

            </div>

            <!-- Filter -->
            <div class="row">

                <div class="input-group">
                    <label>Cari Nama</label>
                    <input type="text" id="searchNama" placeholder="Masukkan nama">
                </div>

                <div class="input-group">
                    <label>Status</label>
                    <select id="searchStatus">
                        <option>Semua</option>
                        <option>Disetujui</option>
                        <option>Pending</option>
                        <option>Ditolak</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Tanggal</label>
                    <input type="date" id="searchTanggal">
                </div>

            </div>

            <!-- Table -->
            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kendaraan</th>
                        <th>Pengemudi</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Keperluan</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody id="dataRiwayat">

                    <?php
                    $no = 1;
                    while ($r = mysqli_fetch_assoc($queryRiwayat)) :
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

                        <td>
                            <?= $r['jam_berangkat']; ?>
                            -
                            <?= $r['jam_kembali']; ?>
                        </td>

                        <td><?= $r['keperluan']; ?></td>

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

                        <td>
                            <div class="action-buttons">

                                <button
                                    class="btn-1 btnDetail"
                                    style="width:auto;padding:8px 15px;font-size:14px;">
                                    Detail
                                </button>

                                <a href="review.php?id=<?= $r['id']; ?>"
                                    class="btn-1"
                                    style="
                                        width:auto;
                                        padding:8px 15px;
                                        font-size:14px;
                                        background:#f59e0b;
                                        text-decoration:none;
                                        display:inline-block;
                                    "
                                >
                                    Review
                                </a>

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

<script src="riwayat.js"></script>
</body>
</html>