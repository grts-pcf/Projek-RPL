<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";
?>

<?php
$kendaraan = mysqli_query($conn,"
    SELECT *
    FROM kendaraan
    ORDER BY merk_jenis
");

$supir = mysqli_query($conn,"
    SELECT *
    FROM supir
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peminjaman Kendaraan</title>

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
            <li class="active">
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
                    <a href="laporan.php"> Laporan</a>
                </li>

                <li>
                    <a href="pengaturan.php">Pengaturan</a>
                </li>

                <li class="logout-menu">
                    <a href="proses/logout.php">Logout</a>
                </li>

            </ul>

    </div>

    <!-- Main -->
    <div class="main-content">

        <!-- Navbar -->
        <div class="navbar">

            <h1>Sistem Peminjaman Kendaraan</h1>

            <div class="profile">
                <a href="LOGIN.html">Admin</a>
            </div>
            
        </div>

        <!-- Cards -->
        <div class="cards">

            <div class="card">
                <h3>Total Kendaraan</h3>
                <p>25</p>
            </div>

            <div class="card">
                <h3>Total Pengemudi</h3>
                <p>10</p>
            </div>

            <div class="card">
                <h3>Peminjaman Hari Ini</h3>
                <p>7</p>
            </div>

            <div class="card">
                <h3>Kendaraan Tersedia</h3>
                <p>18</p>
            </div>

        </div>

        <!-- Form -->
        <div class="form-container">

            <h2>Form Peminjaman Kendaraan</h2>

            <form action="proses/simpan_peminjaman.php" method="POST" enctype="multipart/form-data">

                <div class="input-group">
                    <label>Nama Peminjam</label>
                    <input
                    type="text"
                    name="nama_peminjam"
                    placeholder="Masukkan nama"
                    required>
                </div>

                <div class="input-group">
                    <label>Unit / Bagian</label>
                    <input
                    type="text"
                    name="unit"
                    placeholder="Masukkan unit"
                    required>
                </div>

                <div class="input-group">
                    <label>No Telepon</label>
                    <input
                    type="text"
                    name="no_telepon"
                    placeholder="Masukkan nomor telepon"
                    required>
                </div>

                <div class="row">

                    <div class="input-group">
                        <label>Tanggal Peminjaman</label>
                        <input
                        type="date"
                        name="tanggal_pinjam"
                        required>
                    </div>

                    <div class="input-group">
                        <label>Jam Berangkat</label>
                        <input
                        type="time"
                        name="jam_berangkat"
                        required>
                    </div>

                    <div class="input-group">
                        <label>Tanggal Kembali</label>
                        <input
                        type="date"
                        name="tanggal_kembali"
                        required>
                    </div>

                    <div class="input-group">
                        <label>Jam Kembali</label>
                        <input
                        type="time"
                        name="jam_kembali"
                        required>
                    </div>

                </div>

                <div class="input-group">
                    <label>Keperluan</label>
                    <textarea
                        name="keperluan"
                        placeholder="Masukkan keperluan"
                        required
                    ></textarea>
                </div>

                <div class="input-group">
                    <label>Tujuan</label>
                    <textarea
                        name="tujuan"
                        placeholder="Masukkan tujuan"
                        required
                    ></textarea>
                </div>

                <div class="row">

                    <!-- Jenis Kendaraan -->

                    <div class="input-group">

                        <label>Jenis Kendaraan</label>

                        <select id="jenisKendaraan" name="jenis" required>
                            <option value="">Pilih Jenis Kendaraan</option>
                            <option value="Mobil">Mobil</option>
                            <option value="Motor">Motor</option>
                        </select>

                    </div>

                    <!-- Kendaraan -->

                    <div class="input-group">

                        <label>Kendaraan</label>

                        <select id="kendaraanSelect" name="kendaraan" disabled required>

                            <option value="">Pilih Kendaraan</option>

                            <?php while($k = mysqli_fetch_assoc($kendaraan)): ?>
                                <option
                                    value="<?= $k['no_polisi']; ?>"
                                    data-jenis="<?= $k['jenis']; ?>"
                                >
                                    <?= $k['merk_jenis']; ?> - <?= $k['no_polisi']; ?>
                                </option>
                            <?php endwhile; ?>

                        </select>

                    </div>

                    <!-- Pengemudi -->

                    <div class="input-group">

                        <label>Pengemudi</label>

                        <select id="pengemudiSelect" name="pengemudi" disabled required>

                            <option value="">Pilih Pengemudi</option>

                            <?php while($s = mysqli_fetch_assoc($supir)): ?>
                                <option
                                    value="<?= $s['nama_supir']; ?>"
                                    data-polisi="<?= $s['no_polisi']; ?>"
                                >
                                    <?= $s['nama_supir']; ?>
                                </option>
                            <?php endwhile; ?>

                        </select>

                    </div>

                </div>

                <!-- Upload Tanda Tangan PDF -->

                <div class="input-group">

                    <label>Tanda Tangan (PDF)</label>

                    <input 
                        type="file"
                        accept="application/pdf"
                        id="signatureInput"
                    >

                </div>

                <button type="submit" class="btn-1">
                    Submit Peminjaman
                </button>

            </form>

        </div>

        <!-- Table -->
        <div class="table-container">

            <h2>Riwayat Peminjaman</h2>

            <table>

                <thead>

                    <tr>
                        <th>Nama</th>
                        <th>Kendaraan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    <tr>
                        <td>Jasmine</td>
                        <td>Avanza</td>
                        <td>04 Juni 2026</td>
                        <td>
                            <span class="success">
                                Disetujui
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td>Kevin</td>
                        <td>Hiace</td>
                        <td>05 Juni 2026</td>
                        <td>
                            <span class="pending">
                                Pending
                            </span>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

const signatureInput = document.getElementById("signatureInput");
const preview = document.getElementById("previewSignature");

signatureInput.addEventListener("change", function(){

    const file = this.files[0];

    if(file){

        preview.src = URL.createObjectURL(file);
        preview.style.display = "block";

    }

});

</script>

<script>

const jenisSelect = document.getElementById('jenisKendaraan');
const kendaraanSelect = document.getElementById('kendaraanSelect');
const pengemudiSelect = document.getElementById('pengemudiSelect');

/* FILTER JENIS KENDARAAN */
jenisSelect.addEventListener('change', function(){

    const jenis = this.value;

    kendaraanSelect.selectedIndex = 0;
    pengemudiSelect.selectedIndex = 0;

    kendaraanSelect.disabled = (jenis === '');
    pengemudiSelect.disabled = true;

    Array.from(kendaraanSelect.options).forEach((option, index) => {

        if(index === 0) return;

        if(jenis === ''){
            option.hidden = false;
        } else {
            option.hidden = option.dataset.jenis !== jenis;
        }

    });

});

/* PILIH PENGEMUDI OTOMATIS */
kendaraanSelect.addEventListener('change', function(){

    const noPolisi = this.value;

    pengemudiSelect.selectedIndex = 0;

    if(noPolisi === ''){
        pengemudiSelect.disabled = true;
        return;
    }

    pengemudiSelect.disabled = false;

    Array.from(pengemudiSelect.options).forEach(option => {

        if(option.dataset.polisi === noPolisi){
            option.selected = true;
        }

    });

});

</script>

</body>
</html>