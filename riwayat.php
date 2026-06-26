<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";

// Total Peminjaman
$qTotal = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM riwayat
");
$total = mysqli_fetch_assoc($qTotal);

// Disetujui
$qDisetujui = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM riwayat
WHERE status='disetujui'
");
$disetujui = mysqli_fetch_assoc($qDisetujui);

// Pending
$qPending = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM riwayat
WHERE status='pending'
");
$pending = mysqli_fetch_assoc($qPending);

// Ditolak
$qDitolak = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM riwayat
WHERE status='ditolak'
");
$ditolak = mysqli_fetch_assoc($qDitolak);

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

            <h1>Riwayat Peminjaman</h1>

            <div class="profile">
                <a href="LOGIN.php">Admin</a>
            </div>

        </div>

        <!-- Statistik -->
        <div class="cards">

            <div class="card">
                <h3>Total Peminjaman</h3>
                <p><?= $total['total']; ?></p>
            </div>

            <div class="card">
                <h3>Disetujui</h3>
                <p><?= $disetujui['total']; ?></p>
            </div>

            <div class="card">
                <h3>Pending</h3>
                <p><?= $pending['total']; ?></p>
            </div>

            <div class="card">
                <h3>Ditolak</h3>
                <p><?= $ditolak['total']; ?></p>
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
            " class="btn-1"
            onclick="window.location.href='export_pdf.php'">
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

                        <td data-tanggal="<?= $r['tanggal_pinjam']; ?>">
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

                                <button style="
                                    width:auto;
                                    padding:8px 15px;
                                    font-size:14px; "
                                    class="btn-1 btnDetail"
                                    data-peminjam="<?= $r['nama_peminjam']; ?>"
                                    data-kendaraan="<?= $r['merk_jenis']; ?>"
                                    data-pengemudi="<?= $r['pengemudi']; ?>"
                                    data-tujuan="<?= $r['tujuan']; ?>"
                                    data-tanggal="<?= date('d F Y', strtotime($r['tanggal_pinjam'])); ?>"
                                    data-kembali="<?= date('d F Y', strtotime($r['tanggal_kembali'])); ?>">
                                    Detail
                                </button>

                                <button
                                    class="btn-1 btnReview"
                                    data-id="<?= $r['id']; ?>"
                                    data-nama="<?= $r['nama_peminjam']; ?>"
                                    style="
                                        width:auto;
                                        padding:8px 15px;
                                        font-size:14px;
                                        background:#f59e0b;
                                    "
                                >
                                    Review
                                </button>

                                <button 
                                    style="
                                        width:auto;
                                        padding:8px 15px;
                                        font-size:14px;
                                        background:#ef4444;"
                                    class="btn-1 btnHapus"
                                    data-id="<?= $r['id']; ?>"
                                    data-nama="<?= $r['nama_peminjam']; ?>"
                                >
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

<!-- Modal Detail -->
<div id="modalDetail" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="detail">&times;</span>

        <h2>Detail Peminjaman</h2>

        <div class="detail-container">

            <div class="detail-row">
                <span>Peminjam</span>
                <strong id="d_peminjam"></strong>
            </div>

            <div class="detail-row">
                <span>Kendaraan</span>
                <strong id="d_kendaraan"></strong>
            </div>

            <div class="detail-row">
                <span>Pengemudi</span>
                <strong id="d_pengemudi"></strong>
            </div>

            <div class="detail-row">
                <span>Tujuan</span>
                <strong id="d_tujuan"></strong>
            </div>

            <div class="detail-row">
                <span>Tanggal Pinjam</span>
                <strong id="d_tanggal"></strong>
            </div>

            <div class="detail-row">
                <span>Tanggal Kembali</span>
                <strong id="d_kembali"></strong>
            </div>

        </div>

    </div>

</div>

<!-- Modal Review -->
<div id="modalReview" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="review">&times;</span>

        <p>
            Apakah peminjaman atas nama
            <strong id="reviewNama"></strong>
            akan disetujui?
        </p>

        <br>

        <div style="display:flex; gap:10px;">

            <a
                id="btnSetujui"
                href="#"
                class="btn-1"
                style="
                    text-align: center;
                    background:#22c55e;"
            >
                Setujui
            </a>

            <a
                id="btnTolak"
                href="#"
                class="btn-1"
                style="
                    text-align: center;
                    background:#ef4444;"
            >
                Tolak
            </a>

        </div>

    </div>

</div>

<!-- Modal Hapus -->
<div id="modalHapus" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="hapus">&times;</span>

        <h2>Konfirmasi Hapus</h2>

        <p>
            Yakin ingin menghapus data peminjaman
            <strong id="hapusNama"></strong> ?
        </p>

        <br>

        <div style="display:flex; gap:10px;">

            <a
                id="btnKonfirmasiHapus"
                href="#"
                class="btn-1"
                style="
                    text-align: center;
                    background:#ef4444;"
            >
                Ya, Hapus
            </a>

            <button
                type="button"
                id="btnBatalHapus"
                class="btn-1"
                style="
                    text-align: center;
                    background:#6b7280;"
            >
                Batal
            </button>

        </div>

    </div>

</div>

<script>

const modalDetail =
document.getElementById('modalDetail');

document.querySelectorAll('.btnDetail')
.forEach(btn=>{

    btn.addEventListener('click',function(){

        document.getElementById('d_peminjam').textContent =
            this.dataset.peminjam;

        document.getElementById('d_kendaraan').textContent =
            this.dataset.kendaraan;

        document.getElementById('d_pengemudi').textContent =
            this.dataset.pengemudi;

        document.getElementById('d_tujuan').textContent =
            this.dataset.tujuan;

        document.getElementById('d_tanggal').textContent =
            this.dataset.tanggal;

        document.getElementById('d_kembali').textContent =
            this.dataset.kembali;

        modalDetail.style.display = 'block';

    });

});

const modalReview =
document.getElementById('modalReview');

const reviewNama =
document.getElementById('reviewNama');

const btnSetujui =
document.getElementById('btnSetujui');

const btnTolak =
document.getElementById('btnTolak');

document.querySelectorAll('.btnReview')
.forEach(btn => {

    btn.addEventListener('click', function(){

        let id = this.dataset.id;

        reviewNama.textContent =
        this.dataset.nama;

        btnSetujui.href =
        'proses/update_status.php?id=' +
        id +
        '&status=disetujui';

        btnTolak.href =
        'proses/update_status.php?id=' +
        id +
        '&status=ditolak';

        modalReview.style.display =
        'block';

    });

});

window.addEventListener('click', function(e){

    if(e.target == modalReview){
        modalReview.style.display =
        'none';
    }

});

const modalHapus =
document.getElementById('modalHapus');

const hapusNama =
document.getElementById('hapusNama');

const btnKonfirmasiHapus =
document.getElementById('btnKonfirmasiHapus');

document.querySelectorAll('.btnHapus')
.forEach(btn => {

    btn.addEventListener('click', function(){

        const id = this.dataset.id;

        hapusNama.textContent =
        this.dataset.nama;

        btnKonfirmasiHapus.href =
        'proses/hapus_riwayat.php?id=' + id;

        modalHapus.style.display =
        'block';

    });

});

document.getElementById('btnBatalHapus')
.addEventListener('click', function(){

    modalHapus.style.display =
    'none';

});

document.querySelectorAll('.close').forEach(btn => {

    btn.addEventListener('click', function () {

        if (this.dataset.modal === 'detail') {
            document.getElementById('modalDetail')
            .style.display = 'none';
        }

        if (this.dataset.modal === 'review') {
            document.getElementById('modalReview')
            .style.display = 'none';
        }

        if (this.dataset.modal === 'hapus') {
            document.getElementById('modalHapus')
            .style.display = 'none';
        }

    });

});

const searchNama = document.getElementById('searchNama');
const searchStatus = document.getElementById('searchStatus');
const searchTanggal = document.getElementById('searchTanggal');

function filterRiwayat() {

    const nama = searchNama.value.toLowerCase();
    const status = searchStatus.value.toLowerCase();
    const tanggal = searchTanggal.value;

    const rows = document.querySelectorAll('#dataRiwayat tr');

    rows.forEach(row => {

        const namaRow =
            row.cells[1].textContent.toLowerCase();

        const tanggalRow =
            row.cells[4].textContent.toLowerCase();

        const statusRow =
            row.cells[8].textContent.toLowerCase();

        let tampil = true;

        // Filter nama
        if (
            nama !== '' &&
            !namaRow.includes(nama)
        ) {
            tampil = false;
        }

        // Filter status
        if (
            status !== 'semua' &&
            !statusRow.includes(status)
        ) {
            tampil = false;
        }

        // Filter tanggal
        if (tanggal !== '') {

            const tanggalRow =
                row.cells[4].dataset.tanggal;

            if (tanggalRow !== tanggal) {
                tampil = false;
            }

        }

        row.style.display =
            tampil ? '' : 'none';

    });

}

searchNama.addEventListener('keyup', filterRiwayat);
searchStatus.addEventListener('change', filterRiwayat);
searchTanggal.addEventListener('change', filterRiwayat);

</script>

</body>
</html>