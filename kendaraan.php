<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";

$qTotalKendaraan = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM kendaraan
");
$totalKendaraan = mysqli_fetch_assoc($qTotalKendaraan);

$qMaintenance = mysqli_query($conn,"
SELECT COUNT(DISTINCT id_kendaraan) AS total
FROM jadwal_ganti_oli_kendaraan_operasional
WHERE status='Maintenance'
");
$maintenance = mysqli_fetch_assoc($qMaintenance);

$qDipinjam = mysqli_query($conn,"
SELECT COUNT(DISTINCT kendaraan) AS total
FROM riwayat
WHERE status='disetujui'
AND NOW() BETWEEN
    CONCAT(tanggal_pinjam,' ',jam_berangkat)
AND
    CONCAT(tanggal_kembali,' ',jam_kembali)
");
$dipinjam = mysqli_fetch_assoc($qDipinjam);

$qTersedia = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM kendaraan k
WHERE
NOT EXISTS (
    SELECT 1
    FROM riwayat r
    WHERE r.kendaraan = k.no_polisi
      AND r.status='disetujui'
      AND NOW() BETWEEN
            CONCAT(r.tanggal_pinjam,' ',r.jam_berangkat)
        AND CONCAT(r.tanggal_kembali,' ',r.jam_kembali)
)
AND
NOT EXISTS (
    SELECT 1
    FROM jadwal_ganti_oli_kendaraan_operasional j
    WHERE j.id_kendaraan = k.id_kendaraan
      AND j.status='Maintenance'
)
");
$totalTersedia = mysqli_fetch_assoc($qTersedia);

?>

<?php

$query = mysqli_query($conn, "

SELECT
    k.*,

    (
        SELECT j.status
        FROM jadwal_ganti_oli_kendaraan_operasional j
        WHERE j.id_kendaraan = k.id_kendaraan
        AND j.status = 'Maintenance'
        LIMIT 1
    ) AS status_maintenance,

    (
        SELECT COUNT(*)
        FROM riwayat r
        WHERE r.kendaraan = k.no_polisi
        AND r.status = 'disetujui'
        AND NOW() BETWEEN
            CONCAT(r.tanggal_pinjam,' ',r.jam_berangkat)
        AND
            CONCAT(r.tanggal_kembali,' ',r.jam_kembali)
    ) AS sedang_dipinjam

FROM kendaraan k

ORDER BY k.id_kendaraan ASC

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
                <a href="jadwal.php">Jadwal Maintenance</a>
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
                <a href="LOGIN.php">
                    <?= htmlspecialchars($_SESSION['admin']); ?>
                </a>
            </div>

        </div>

        <!-- Statistik -->
        <div class="cards">

            <div class="card">
                <h3>Total Kendaraan</h3>
                <p><?= $totalKendaraan['total']; ?></p>
            </div>
            
            <div class="card">
                <h3>Tersedia</h3>
                <p><?= $totalTersedia['total']; ?></p>
            </div>

            <div class="card">
                <h3>Dipinjam</h3>
                <p><?= $dipinjam['total']; ?></p>
            </div>

            <div class="card">
                <h3>Maintenance</h3>
                <p><?= $maintenance['total']; ?></p>
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
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="dataKendaraan">

                <?php
                $no = 1;

                while($row = mysqli_fetch_assoc($query)):

                    if(!empty($row['status_maintenance']))
                    {
                        $statusKendaraan = 'Maintenance';
                    }
                    elseif($row['sedang_dipinjam'] > 0)
                    {
                        $statusKendaraan = 'Dipinjam';
                    }
                    else
                    {
                        $statusKendaraan = 'Tersedia';
                    }

                ?>

                <tr
                    data-kendaraan="<?= strtolower($row['merk_jenis'].' '.$row['no_polisi']); ?>"
                    data-status="<?= strtolower($statusKendaraan); ?>"
                >

                    <td><?= $no++; ?></td>

                    <td><?= htmlspecialchars($row['merk_jenis']); ?></td>

                    <td><?= htmlspecialchars($row['tahun']); ?></td>

                    <td><?= htmlspecialchars($row['no_polisi']); ?></td>

                    <td><?= htmlspecialchars($row['tgl_pajak_stnk']); ?></td>

                    <td>
                        <?php if($row['jenis'] == "Mobil"): ?>
                            <span class="badge success">Mobil</span>
                        <?php else: ?>
                            <span class="badge pending">Motor</span>
                        <?php endif; ?>
                    </td>

                    <td>

                    <?php if($statusKendaraan == 'Maintenance'): ?>

                        <span class="badge maintenance">
                            Maintenance
                        </span>

                    <?php elseif($statusKendaraan == 'Dipinjam'): ?>

                        <span class="badge pending">
                            Dipinjam
                        </span>

                    <?php else: ?>

                        <span class="badge success">
                            Tersedia
                        </span>

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
                                data-id="<?= $row['id_kendaraan']; ?>"
                                data-merk="<?= htmlspecialchars($row['merk_jenis']); ?>"
                                data-tahun="<?= $row['tahun']; ?>"
                                data-polisi="<?= htmlspecialchars($row['no_polisi']); ?>"
                                data-pajak="<?= $row['tgl_pajak_stnk']; ?>"
                                data-jenis="<?= htmlspecialchars($row['jenis']); ?>">
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
                                data-id="<?= $row['id_kendaraan']; ?>"
                                data-merk="<?= htmlspecialchars($row['merk_jenis']); ?>"
                                data-tahun="<?= $row['tahun']; ?>"
                                data-polisi="<?= htmlspecialchars($row['no_polisi']); ?>"
                                data-pajak="<?= $row['tgl_pajak_stnk']; ?>"
                                data-jenis="<?= htmlspecialchars($row['jenis']); ?>"
                                data-status="<?= $statusKendaraan; ?>">
                                Detail
                            </button>

                            <button
                                class="btn-1 btnHapus"
                                data-id="<?= $row['id_kendaraan']; ?>"
                                data-merk="<?= htmlspecialchars($row['merk_jenis']); ?>"
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

<div id="modalTambahKendaraan" class="modal">

    <div class="modal-content">

        <span
            class="close"
            data-modal="tambah"
        >&times;</span>

        <h2>Tambah Kendaraan</h2>

        <form
            action="proses/tambah_kendaraan.php"
            method="POST">

            <div class="input-group">
                <label>Merk / Jenis</label>
                <input
                    type="text"
                    name="merk_jenis"
                    required>
            </div>

            <div class="input-group">
                <label>Tahun</label>
                <input
                    type="number"
                    name="tahun"
                    required>
            </div>

            <div class="input-group">
                <label>No Polisi</label>
                <input
                    type="text"
                    name="no_polisi"
                    required>
            </div>

            <div class="input-group">
                <label>Tanggal Pajak STNK</label>
                <input
                    type="date"
                    name="tgl_pajak_stnk"
                    required>
            </div>

            <div class="input-group">
                <label>Jenis Kendaraan</label>

                <select
                    name="jenis"
                    required>

                    <option value="">
                        Pilih Jenis
                    </option>

                    <option value="Mobil">
                        Mobil
                    </option>

                    <option value="Motor">
                        Motor
                    </option>

                </select>

            </div>

            <button
                type="submit"
                class="btn-1">
                Simpan
            </button>

        </form>

    </div>

</div>

<div id="modalEditKendaraan" class="modal">

    <div class="modal-content">

        <span
            class="close"
            data-modal="edit"
        >&times;</span>

        <h2>Edit Kendaraan</h2>

        <form
            action="proses/update_kendaraan.php"
            method="POST">

            <input
                type="hidden"
                id="edit_id"
                name="id_kendaraan">

            <div class="input-group">
                <label>Merk/Jenis</label>
                <input
                    type="text"
                    id="edit_merk"
                    name="merk_jenis"
                    required>
            </div>

            <div class="input-group">
                <label>Tahun</label>
                <input
                    type="number"
                    id="edit_tahun"
                    name="tahun"
                    required>
            </div>

            <div class="input-group">
                <label>No Polisi</label>
                <input
                    type="text"
                    id="edit_polisi"
                    name="no_polisi"
                    required>
            </div>

            <div class="input-group">
                <label>Tanggal Pajak STNK</label>
                <input
                    type="date"
                    id="edit_pajak"
                    name="tanggal_pajak_stnk"
                    required>
            </div>

            <div class="input-group">
                <label>Jenis</label>
                <input
                    type="text"
                    id="edit_jenis"
                    name="jenis"
                    required>
            </div>

            <button
                type="submit"
                class="btn-1">
                Simpan Perubahan
            </button>

        </form>

    </div>

</div>

<div id="modalDetailKendaraan" class="modal">

    <div class="modal-content">

        <span
            class="close"
            data-modal="detail"
        >&times;</span>

        <h2>Detail Kendaraan</h2>

        <table style="width:100%;">

            <tr>
                <td><b>Merk/Jenis</b></td>
                <td id="detail_merk"></td>
            </tr>

            <tr>
                <td><b>Tahun</b></td>
                <td id="detail_tahun"></td>
            </tr>

            <tr>
                <td><b>No Polisi</b></td>
                <td id="detail_polisi"></td>
            </tr>

            <tr>
                <td><b>Tanggal Pajak</b></td>
                <td id="detail_pajak"></td>
            </tr>

            <tr>
                <td><b>Jenis</b></td>
                <td id="detail_jenis"></td>
            </tr>

            <tr>
                <td><b>Status</b></td>
                <td id="detail_status"></td>
            </tr>

        </table>

    </div>

</div>

<div id="modalHapusKendaraan" class="modal">

    <div class="modal-content">

        <span
            class="close"
            data-modal="hapus"
        >&times;</span>

        <h2>Hapus Kendaraan</h2>

        <p>
            Yakin ingin menghapus kendaraan:
            <b id="namaKendaraanHapus"></b> ?
        </p>

        <form
            action="proses/hapus_kendaraan.php"
            method="GET">

            <input
                type="hidden"
                id="hapus_id"
                name="id">

            <div
                style="
                    display:flex;
                    gap:10px;
                    justify-content:center;
                    margin-top:20px;
                ">

                <button
                    type="submit"
                    class="btn-1"
                    style=
                    "background:#ef4444; 
                    text-align: center;">
                    Hapus
                </button>

                <button
                    type="button"
                    class="btn-1"
                    style=
                    "background:#6b7280; 
                    text-align: center;"
                    onclick="
                        document.getElementById('modalHapusKendaraan')
                        .style.display='none';
                    ">
                    Batal
                </button>

            </div>

        </form>

    </div>

</div>

<script>
const searchKendaraan =
document.getElementById('searchKendaraan');

const searchStatus =
document.getElementById('searchStatus');

function filterKendaraan()
{
    const keyword =
        searchKendaraan.value.toLowerCase();

    const status =
        searchStatus.value.toLowerCase();

    const rows =
        document.querySelectorAll('#dataKendaraan tr');

    rows.forEach(row => {

        const kendaraan =
            row.dataset.kendaraan;

        const statusRow =
            row.dataset.status;

        let tampil = true;

        // Filter kendaraan
        if (
            keyword !== '' &&
            !kendaraan.includes(keyword)
        ) {
            tampil = false;
        }

        // Filter status
        if (
            status !== 'semua' &&
            statusRow !== status
        ) {
            tampil = false;
        }

        row.style.display =
            tampil ? '' : 'none';

    });
}

searchKendaraan.addEventListener(
    'keyup',
    filterKendaraan
);

searchStatus.addEventListener(
    'change',
    filterKendaraan
);

const modalTambah =
document.getElementById(
    'modalTambahKendaraan'
);

document
.getElementById('btnTambahKendaraan')
.addEventListener('click', function(){

    modalTambah.style.display =
    'block';

});

const modalEdit =
document.getElementById(
    'modalEditKendaraan'
);

document.querySelectorAll('.btnEdit')
.forEach(btn => {

    btn.addEventListener('click', function(){

        document.getElementById('edit_id')
        .value = this.dataset.id;

        document.getElementById('edit_merk')
        .value = this.dataset.merk;

        document.getElementById('edit_tahun')
        .value = this.dataset.tahun;

        document.getElementById('edit_polisi')
        .value = this.dataset.polisi;

        document.getElementById('edit_pajak')
        .value = this.dataset.pajak;

        document.getElementById('edit_jenis')
        .value = this.dataset.jenis;

        modalEdit.style.display =
        'block';

    });

});

const modalDetail =
document.getElementById(
    'modalDetailKendaraan'
);

document.querySelectorAll('.btnDetail')
.forEach(btn => {

    btn.addEventListener('click', function(){

        document.getElementById('detail_merk')
        .textContent = this.dataset.merk;

        document.getElementById('detail_tahun')
        .textContent = this.dataset.tahun;

        document.getElementById('detail_polisi')
        .textContent = this.dataset.polisi;

        document.getElementById('detail_pajak')
        .textContent = this.dataset.pajak;

        document.getElementById('detail_jenis')
        .textContent = this.dataset.jenis;

        document.getElementById('detail_status')
        .textContent = this.dataset.status;

        modalDetail.style.display =
        'block';

    });

});

const modalHapus =
document.getElementById(
    'modalHapusKendaraan'
);

document.querySelectorAll('.btnHapus')
.forEach(btn => {

    btn.addEventListener('click', function(){

        document.getElementById(
            'hapus_id'
        ).value = this.dataset.id;

        document.getElementById(
            'namaKendaraanHapus'
        ).textContent =
        this.dataset.merk;

        modalHapus.style.display =
        'block';

    });

});

document.querySelectorAll(".close").forEach(btn=>{

    btn.addEventListener("click",function(){

        if(this.dataset.modal === 'tambah'){
            modalTambah.style.display =
            'none';
        }
        
        if(this.dataset.modal === 'edit'){
            modalEdit.style.display =
            'none';
        }

        if(this.dataset.modal === 'detail'){
            modalDetail.style.display =
            'none';
        }

        if(this.dataset.modal === 'hapus'){
            modalHapus.style.display =
            'none';
        }
        
    });
});

</script>
</body>
</html>