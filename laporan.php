<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";

$qTotalPeminjaman = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM riwayat
");
$totalPeminjaman = mysqli_fetch_assoc($qTotalPeminjaman);

$qKendaraanAktif = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM kendaraan
");
$kendaraanAktif = mysqli_fetch_assoc($qKendaraanAktif);

$qTotalPengemudi = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM supir
");
$totalPengemudi = mysqli_fetch_assoc($qTotalPengemudi);

$qLaporanBulan = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM riwayat
WHERE MONTH(tanggal_pinjam)=MONTH(CURDATE())
AND YEAR(tanggal_pinjam)=YEAR(CURDATE())
");
$laporanBulan = mysqli_fetch_assoc($qLaporanBulan);

$queryLaporan = mysqli_query($conn,"
    SELECT
        r.*,
        k.merk_jenis
    FROM riwayat r
    LEFT JOIN kendaraan k
        ON r.kendaraan = k.no_polisi
    ORDER BY r.id DESC
");

// Data jadwal ganti oli
$queryGantiOli = mysqli_query($conn, "
    SELECT *
    FROM jadwal_ganti_oli_kendaraan_operasional
    ORDER BY tanggal_service DESC
");

// Data pengisian BBM
$queryBBM = mysqli_query($conn,"
    SELECT
        p.*,
        k.no_polisi,
        s.nama_supir
    FROM pengisian_bbm p
    LEFT JOIN kendaraan k
        ON p.id_kendaraan = k.id_kendaraan
    LEFT JOIN supir s
        ON p.id_supir = s.id
    ORDER BY p.tanggal_pengisian DESC
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

            <li>
                <a href="jadwal.php">Jadwal Maintenance</a>
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
                <a href="LOGIN.php">Admin</a>
            </div>

        </div>

        <!-- Cards -->
        <div class="cards">

            <div class="card">
                <h3>Total Peminjaman</h3>
                <p><?= $totalPeminjaman['total']; ?></p>
            </div>

            <div class="card">
                <h3>Kendaraan Aktif</h3>
                <p><?= $kendaraanAktif['total']; ?></p>
            </div>

            <div class="card">
                <h3>Total Pengemudi</h3>
                <p><?= $totalPengemudi['total']; ?></p>
            </div>

            <div class="card">
                <h3>Laporan Bulan Ini</h3>
                <p><?= $laporanBulan['total']; ?></p>
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

                <button
                    id="btnExportExcel"
                    style="
                        width:auto;
                        padding:12px 20px;"
                    class="btn-1"
                >
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

                        <td
                            data-pinjam="<?= $r['tanggal_pinjam']; ?>"
                            data-kembali="<?= $r['tanggal_kembali']; ?>"
                        >
                            <?= date('d F Y', strtotime($r['tanggal_pinjam'])); ?>
                            -
                            <?= date('d F Y', strtotime($r['tanggal_kembali'])); ?>
                        </td>
                        <td><?= $r['tujuan']; ?></td>

                        <td>

                            <?php if($r['status'] == 'disetujui'): ?>

                                <span class="badge success">
                                    Disetujui
                                </span>

                            <?php elseif($r['status'] == 'pending'): ?>

                                <span class="badge pending">
                                    Pending
                                </span>

                            <?php else: ?>

                                <span class="badge maintenance">
                                    Ditolak
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

        <div class="table-container" style="margin-top:40px;">

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:20px;
            ">
                <h2>Laporan Maintenance</h2>

                <button
                    id="btnExportMaintenance"
                    class="btn-1"
                    style="width:auto;padding:12px 20px;">
                    Export Excel
                </button>
            </div>

            <div class="row" style="margin-bottom:20px;">
                <div class="input-group">
                    <label>Tanggal Awal</label>
                    <input type="date" id="tglOliAwal">
                </div>

                <div class="input-group">
                    <label>Tanggal Akhir</label>
                    <input type="date" id="tglOliAkhir">
                </div>

                <div class="input-group">
                    <button type="button"
                            id="filterOli"
                            class="btn-1"
                            style="margin-top:28px;">
                        Generate
                    </button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Polisi</th>
                        <th>Tanggal Service</th>
                        <th>No SPK</th>
                        <th>KM Ganti Oli</th>
                        <th>KM Selanjutnya</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody id="dataGantiOli">
                    <?php
                    $no = 1;
                    while($oli = mysqli_fetch_assoc($queryGantiOli)) :
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $oli['no_polisi']; ?></td>

                        <td
                            data-tanggal="<?= $oli['tanggal_service']; ?>">
                            <?= date('d-m-Y', strtotime($oli['tanggal_service'])); ?>
                        </td>

                        <td><?= $oli['no_spk']; ?></td>
                        <td><?= $oli['km_ganti_oli']; ?></td>
                        <td><?= $oli['km_ganti_oli_selanjutnya']; ?></td>
                        <td class="keterangan-maintenance" style="max-width:300px; white-space:normal;"><?= nl2br(htmlspecialchars($oli['keterangan'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>

        <div class="table-container" style="margin-top:40px;">

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:20px;
            ">
                <h2>Laporan Pengisian BBM</h2>

                <button
                    id="btnExportBBM"
                    class="btn-1"
                    style="width:auto;padding:12px 20px;">
                    Export Excel
                </button>
            </div>

            <div class="row" style="margin-bottom:20px;">
                <div class="input-group">
                    <label>Tanggal Awal</label>
                    <input type="date" id="tglBBMAwal">
                </div>

                <div class="input-group">
                    <label>Tanggal Akhir</label>
                    <input type="date" id="tglBBMAkhir">
                </div>

                <div class="input-group">
                    <button type="button"
                            id="filterBBM"
                            class="btn-1"
                            style="margin-top:28px;">
                        Generate
                    </button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Polisi</th>
                        <th>Supir</th>
                        <th>Tanggal</th>
                        <th>KM Sebelum</th>
                        <th>KM Sesudah</th>
                        <th>KM Terpakai</th>
                        <th>Jumlah Pengisian</th>
                    </tr>
                </thead>

                <tbody id="dataBBM">
                    <?php
                    $no = 1;
                    while($bbm = mysqli_fetch_assoc($queryBBM)) :
                    ?>

                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $bbm['no_polisi']; ?></td>
                        <td><?= $bbm['nama_supir']; ?></td>

                        <td data-tanggal="<?= $bbm['tanggal_pengisian']; ?>">
                            <?= date('d-m-Y', strtotime($bbm['tanggal_pengisian'])); ?>
                        </td>

                        <td><?= number_format($bbm['km_sebelum']); ?></td>
                        <td><?= number_format($bbm['km_sesudah']); ?></td>
                        <td><?= number_format($bbm['km_terpakai']); ?></td>
                        <td>Rp <?= number_format($bbm['jumlah_pengisian'],0,',','.'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>

    </div>

</div>

<script>

document.getElementById("btnGenerate")
.addEventListener("click", function(){

    const tglAwal =
        document.getElementById("tglAwal").value;

    const tglAkhir =
        document.getElementById("tglAkhir").value;

    const status =
        document.getElementById("statusFilter")
        .value
        .toLowerCase();

    const rows =
        document.querySelectorAll("#dataLaporan tr");

    rows.forEach(function(row){

        const pinjam =
            row.cells[4].dataset.pinjam;

        const kembali =
            row.cells[4].dataset.kembali;

        const statusData =
            row.cells[6]
            .textContent
            .trim()
            .toLowerCase();

        const cocokStatus =
            status === "semua" ||
            statusData.includes(status);

        let cocokTanggal = true;

        if (tglAwal !== '' && tglAkhir !== '') {

            cocokTanggal =
                (pinjam <= tglAkhir) &&
                (kembali >= tglAwal);

        }

        row.style.display =
            (cocokStatus && cocokTanggal)
            ? ''
            : 'none';

    });

});

document.getElementById("btnExportExcel")
.addEventListener("click", function(){

    const tglAwal =
        document.getElementById("tglAwal").value;

    const tglAkhir =
        document.getElementById("tglAkhir").value;

    const status =
        document.getElementById("statusFilter").value;

    window.location.href =
        "export_excel.php" +
        "?tglAwal=" + encodeURIComponent(tglAwal) +
        "&tglAkhir=" + encodeURIComponent(tglAkhir) +
        "&status=" + encodeURIComponent(status);

});

document.getElementById("filterOli")
.addEventListener("click", function(){

    const awal =
        document.getElementById("tglOliAwal").value;

    const akhir =
        document.getElementById("tglOliAkhir").value;

    const rows =
        document.querySelectorAll("#dataGantiOli tr");

    rows.forEach(function(row){

        const tanggal =
            row.cells[2].dataset.tanggal;

        let tampil = true;

        if(awal !== '' && akhir !== ''){
            tampil =
                tanggal >= awal &&
                tanggal <= akhir;
        }

        row.style.display =
            tampil ? '' : 'none';
    });
});

document.getElementById("filterBBM")
.addEventListener("click", function(){

    const awal =
        document.getElementById("tglBBMAwal").value;

    const akhir =
        document.getElementById("tglBBMAkhir").value;

    const rows =
        document.querySelectorAll("#dataBBM tr");

    rows.forEach(function(row){

        const tanggal =
            row.cells[2].dataset.tanggal;

        let tampil = true;

        if(awal !== '' && akhir !== ''){
            tampil =
                tanggal >= awal &&
                tanggal <= akhir;
        }

        row.style.display =
            tampil ? '' : 'none';
    });
});

function setDateRange(startId, endId) {
    const start = document.getElementById(startId);
    const end = document.getElementById(endId);

    start.addEventListener('change', function () {
        end.min = this.value;

        if (end.value < this.value) {
            end.value = '';
        }
    });

    end.addEventListener('change', function () {
        start.max = this.value;

        if (start.value > this.value) {
            start.value = '';
        }
    });
}

setDateRange('tglAwal', 'tglAkhir');
setDateRange('tglOliAwal', 'tglOliAkhir');
setDateRange('tglBBMAwal', 'tglBBMAkhir');

document.getElementById("btnExportMaintenance")
.addEventListener("click", function(){

    const awal =
        document.getElementById("tglOliAwal").value;

    const akhir =
        document.getElementById("tglOliAkhir").value;

    window.location.href =
        "export_maintenance.php" +
        "?tglAwal=" + encodeURIComponent(awal) +
        "&tglAkhir=" + encodeURIComponent(akhir);

});

document.getElementById("btnExportBBM")
.addEventListener("click", function(){

    const awal =
        document.getElementById("tglBBMAwal").value;

    const akhir =
        document.getElementById("tglBBMAkhir").value;

    window.location.href =
        "export_bbm.php" +
        "?tglAwal=" + encodeURIComponent(awal) +
        "&tglAkhir=" + encodeURIComponent(akhir);

});

</script>
</body>
</html>