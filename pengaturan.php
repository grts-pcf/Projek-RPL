<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>

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

            <li>
                <a href="laporan.php">Laporan</a>
            </li>

            <li class="active">
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

            <h1>Pengaturan Sistem</h1>

            <div class="profile">
                <a href="LOGIN.html">Admin</a>
            </div>

        </div>

        <!-- Pengaturan Akun -->
        <div class="form-container">

            <h2>Pengaturan Akun</h2>

            <form>

                <div class="input-group">
                    <label>Nama Admin</label>
                    <input type="text" value="Administrator">
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" value="admin@ubd.ac.id">
                </div>

                <div class="input-group">
                    <label>Password Baru</label>
                    <input type="password" placeholder="Masukkan password baru">
                </div>

                <button type="submit" class="btn-1">
                    Simpan Perubahan
                </button>

            </form>

        </div>

        <!-- Pengaturan Sistem -->
        <div class="form-container">

            <h2>Pengaturan Sistem</h2>

            <form>

                <div class="input-group">
                    <label>Nama Sistem</label>
                    <input type="text" value="Sistem Peminjaman Kendaraan">
                </div>

                <div class="input-group">
                    <label>Nama Instansi</label>
                    <input type="text" value="Universitas Buddhi Dharma">
                </div>

                <div class="input-group">
                    <label>Alamat Email Sistem</label>
                    <input type="email" value="transport@ubd.ac.id">
                </div>

                <div class="input-group">
                    <label>Nomor Telepon</label>
                    <input type="text" value="021-12345678">
                </div>

                <button type="submit" class="btn-1">
                    Update Sistem
                </button>

            </form>

        </div>

        <!-- Pengaturan Tampilan -->
        <div class="table-container">

            <h2>Pengaturan Tampilan</h2>

            <table>

                <thead>

                    <tr>
                        <th>Fitur</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <tr>
                        <td>Dark Mode</td>
                        <td>
                            <span class="pending">
                                Nonaktif
                            </span>
                        </td>
                        <td>

                            <button style="
                                width:auto;
                                padding:8px 15px;
                                font-size:14px;
                            " class="btn-1">
                                Aktifkan
                            </button>

                        </td>
                    </tr>

                    <tr>
                        <td>Notifikasi Email</td>
                        <td>
                            <span class="success">
                                Aktif
                            </span>
                        </td>
                        <td>

                            <button style="
                                width:auto;
                                padding:8px 15px;
                                font-size:14px;
                                background:#ef4444;
                            " class="btn-1">
                                Nonaktifkan
                            </button>

                        </td>
                    </tr>

                    <tr>
                        <td>Backup Database</td>
                        <td>
                            <span class="success">
                                Aktif
                            </span>
                        </td>
                        <td>

                            <button style="
                                width:auto;
                                padding:8px 15px;
                                font-size:14px;
                                background:#10b981;
                            " class="btn-1">
                                Backup Sekarang
                            </button>

                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>