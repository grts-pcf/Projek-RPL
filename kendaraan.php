<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";
?>

<?php

$query = mysqli_query($conn, "
SELECT *
FROM kendaraan
ORDER BY id_kendaraan ASC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Kendaraan</title>

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

            <li class="active">
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

            <h1>Master Kendaraan</h1>

            <div class="profile">
                <a href="LOGIN.php">Admin</a>
            </div>

        </div>

        <!-- Statistik -->
        <div class="cards">

            <div class="card">
                <h3>Total Kendaraan</h3>
                <p>8</p>
            </div>

            <div class="card">
                <h3>Tersedia</h3>
                <p>1</p>
            </div>

            <div class="card">
                <h3>Dipinjam</h3>
                <p>6</p>
            </div>

            <div class="card">
                <h3>Maintenance</h3>
                <p>1</p>
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

                <h2>Daftar Kendaraan</h2>

                <button id="btnTambahKendaraan" style="
                    width:auto;
                    padding:12px 20px;
                " class="btn-1">
                    + Tambah Kendaraan
                </button>

            </div>

            <!-- Filter -->
            <div class="row">

                <div class="input-group">
                    <label>Cari Kendaraan</label>
                    <input type="text" id="searchKendaraan" placeholder="Masukkan nama kendaraan">
                </div>

                <div class="input-group">
                    <label>Status</label>
                    <select id="searchStatus">
                        <option>Semua</option>
                        <option>Tersedia</option>
                        <option>Dipinjam</option>
                        <option>Maintenance</option>
                    </select>
                </div>

            </div>

            <!-- Table -->
            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Merk/Jenis</th>
                        <th>Tahun</th>
                        <th>No Polisi</th>
                        <th>Tanggal Pajak STNK</th>
                        <th>Jenis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="dataKendaraan">

                <?php
                $no = 1;

                while($row = mysqli_fetch_assoc($query)):
                ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td><?= htmlspecialchars($row['merk_jenis']); ?></td>

                    <td><?= htmlspecialchars($row['tahun']); ?></td>

                    <td><?= htmlspecialchars($row['no_polisi']); ?></td>

                    <td><?= htmlspecialchars($row['tgl_pajak_stnk']); ?></td>

                    <td>
                        <?php if($row['jenis'] == "Mobil"): ?>
                            <span class="success">Mobil</span>
                        <?php else: ?>
                            <span class="pending">Motor</span>
                        <?php endif; ?>
                    </td>

                    <td>

                        <div class="action-buttons">

                            <button
                                class="btn-1 btnEdit"
                                style="
                                    width:auto;
                                    padding:8px 15px;
                                    font-size:14px;
                                "
                                data-id="<?= $row['id_kendaraan']; ?>">
                                Edit
                            </button>

                            <button
                                class="btn-1 btnDetail"
                                style="
                                    width:auto;
                                    padding:8px 15px;
                                    font-size:14px;
                                    background:#f59e0b;
                                "
                                data-id="<?= $row['id_kendaraan']; ?>">
                                Detail
                            </button>

                            <a
                                href="proses/hapus_kendaraan.php?id=<?= $row['id_kendaraan']; ?>"
                                onclick="return confirm('Yakin ingin menghapus kendaraan ini?')"
                                class="btn-1"
                                style="
                                    width:auto;
                                    padding:8px 15px;
                                    font-size:14px;
                                    background:#ef4444;
                                    color:white;
                                    text-decoration:none;
                                    display:inline-block;
                                ">
                                Hapus
                            </a>

                        </div>

                    </td>

                </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script src="kendaraan.js"></script>
</body>
</html>