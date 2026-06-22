<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";

$query = mysqli_query($conn, "
    SELECT *
    FROM supir
    ORDER BY id ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengemudi</title>

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

            <li class="active">
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

            <h1>Data Pengemudi</h1>

            <div class="profile">
                <a href="LOGIN.php">Admin</a>
            </div>

        </div>

        <!-- Cards -->
        <?php
        $total = mysqli_num_rows($query);
        mysqli_data_seek($query,0);
        ?>

        <div class="cards">

            <div class="card">
                <h3>Total Pengemudi</h3>
                <p><?= $total; ?></p>
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

                <h2>Daftar Pengemudi</h2>

                <button id="btnTambahPengemudi" style="
                    width:auto;
                    padding:12px 20px;
                " class="btn-1">
                    + Tambah Pengemudi
                </button>

            </div>

            <!-- Filter -->
            <div class="row">

                <div class="input-group">
                    <label>Cari Pengemudi</label>
                   <input type="text" id="searchPengemudi" placeholder="Masukkan nama pengemudi">
                </div>

            </div>

            <!-- Table -->
            <table>

            <thead>

                <tr>

                    <th>No</th>
                    <th>Nama Pengemudi</th>
                    <th>No Polisi</th>
                    <th>Aksi</th>

                </tr>

            </thead>

                <tbody id="dataPengemudi">

                    <?php
                    $no = 1;

                    while($row = mysqli_fetch_assoc($query)):
                    ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td><?= htmlspecialchars($row['nama_supir']); ?></td>

                        <td><?= htmlspecialchars($row['no_polisi']); ?></td>

                        <td>

                            <div class="action-buttons">

                                <button
                                    class="btn-1 btnEdit"
                                    data-id="<?= $row['id']; ?>"
                                    style="
                                        width:auto;
                                        padding:8px 15px;
                                        font-size:14px;
                                    ">
                                    Edit
                                </button>

                                <button
                                    class="btn-1 btnDetail"
                                    data-id="<?= $row['id']; ?>"
                                    style="
                                        width:auto;
                                        padding:8px 15px;
                                        font-size:14px;
                                        background:#f59e0b;
                                    ">
                                    Detail
                                </button>

                                <a
                                    href="proses/hapus_supir.php?id=<?= $row['id']; ?>"
                                    onclick="return confirm('Yakin ingin menghapus pengemudi ini?')"
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

<script src="pengemudi.js"></script>
</body>
</html>