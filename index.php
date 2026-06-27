<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";
$qTotalKendaraan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM kendaraan");
$totalKendaraan = mysqli_fetch_assoc($qTotalKendaraan);


$qTotalSupir = mysqli_query($conn, "SELECT COUNT(*) AS total FROM supir");
$totalSupir = mysqli_fetch_assoc($qTotalSupir);


$qHariIni = mysqli_query($conn,"
    SELECT COUNT(*) AS total
    FROM riwayat
    WHERE tanggal_pinjam = CURDATE()
");
$hariIni = mysqli_fetch_assoc($qHariIni);

$qTersedia = mysqli_query($conn, "
SELECT COUNT(*) AS total
FROM kendaraan k
WHERE
NOT EXISTS (
    SELECT 1
    FROM riwayat r
    WHERE r.kendaraan = k.no_polisi
      AND r.status = 'disetujui'
      AND NOW() BETWEEN
          CONCAT(r.tanggal_pinjam,' ',r.jam_berangkat)
      AND
          CONCAT(r.tanggal_kembali,' ',r.jam_kembali)
)
AND
NOT EXISTS (
    SELECT 1
    FROM jadwal_ganti_oli_kendaraan_operasional j
    WHERE j.id_kendaraan = k.id_kendaraan
      AND j.status = 'Maintenance'
);
");

$totalTersedia = mysqli_fetch_assoc($qTersedia);

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

<?php
$kendaraan = mysqli_query($conn,"
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
ORDER BY k.merk_jenis ASC
");

$supir = mysqli_query($conn,"
    SELECT
    s.*,

    (
        SELECT COUNT(*)
        FROM riwayat r
        WHERE r.pengemudi = s.nama_supir
        AND r.status = 'disetujui'
        AND NOW() BETWEEN
            CONCAT(r.tanggal_pinjam,' ',r.jam_berangkat)
        AND
            CONCAT(r.tanggal_kembali,' ',r.jam_kembali)
    ) AS sedang_bertugas

FROM supir s

ORDER BY s.nama_supir ASC
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
                    <a href="jadwal.php">Jadwal Maintenance</a>
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
                <a href="LOGIN.php">Admin</a>
            </div>
            
        </div>

        <!-- Cards -->
        <div class="cards">

            <div class="card">
                <h3>Total Kendaraan</h3>
                <p><?= $totalKendaraan['total']; ?></p>
                
            </div>

            <div class="card">
                <h3>Total Pengemudi</h3>
                <p><?= $totalSupir['total']; ?></p>
            </div>

            <div class="card">
                <h3>Peminjaman Hari Ini</h3>
                <p><?= $hariIni['total']; ?></p>
            </div>

            <div class="card">
                <h3>Kendaraan Tersedia</h3>
                <p><?= $totalTersedia['total']; ?></p>
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
                        id="tanggal_pinjam"
                        name="tanggal_pinjam"
                        required>
                    </div>

                    <div class="input-group">
                        <label>Jam Berangkat</label>
                        <input
                        type="time"
                        id="jam_berangkat"
                        name="jam_berangkat"
                        required>
                    </div>

                    <div class="input-group">
                        <label>Tanggal Kembali</label>
                        <input
                        type="date"
                        id="tanggal_kembali"
                        name="tanggal_kembali"
                        required>
                    </div>

                    <div class="input-group">
                        <label>Jam Kembali</label>
                        <input
                        type="time"
                        id="jam_kembali"
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
                                    <?php

                                $status = "Tersedia";

                                if(!empty($k['status_maintenance']))
                                {
                                    $status = "Maintenance";
                                }
                                elseif($k['sedang_dipinjam'] > 0)
                                {
                                    $status = "Dipakai";
                                }

                                ?>
                                <option
                                    value="<?= $k['no_polisi']; ?>"
                                    data-jenis="<?= $k['jenis']; ?>"
                                    <?= $status != "Tersedia" ? "disabled" : ""; ?>
                                >
                                    <?= $k['merk_jenis']; ?> - <?= $k['no_polisi']; ?>

                                    <?= $status != "Tersedia"
                                    ? " (".$status.")"
                                    : ""; ?>
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

                                <?php
                                $statusSupir =
                                    ($s['sedang_bertugas'] > 0)
                                    ? "Bertugas"
                                    : "Tersedia";
                                ?>

                                <option
                                    value="<?= $s['nama_supir']; ?>"
                                    data-polisi="<?= $s['no_polisi']; ?>"
                                    <?= $statusSupir == "Bertugas" ? "disabled" : ""; ?>
                                >
                                    <?= $s['nama_supir']; ?>

                                    <?= $statusSupir == "Bertugas"
                                        ? " (Bertugas)"
                                        : ""; ?>

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
                        id="signatureInput"
                        name="surat_pdf"
                        accept=".pdf,application/pdf"
                        required
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

                    <?php
                    $no = 1;
                    while ($r = mysqli_fetch_assoc($queryRiwayat)) :
                    ?>

                    <tr>
                        <td><?= $r['nama_peminjam']; ?></td>
                        <td><?= $r['merk_jenis']; ?></td>
                        <td>
                            <?= date('d F Y', strtotime($r['tanggal_pinjam'])); ?> 
                            -
                            <?= date('d F Y', strtotime($r['tanggal_kembali'])); ?>
                        </td>
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
        pengemudiSelect.selectedIndex = 0;
        pengemudiSelect.disabled = true;
        return;
    }

    let ditemukan = false;

    Array.from(pengemudiSelect.options).forEach(option => {

        if(
            option.dataset.polisi === noPolisi &&
            !option.disabled
        ){
            option.selected = true;
            ditemukan = true;
        }
    });

    pengemudiSelect.disabled = !ditemukan;
});

const tanggalPinjam = document.getElementById('tanggal_pinjam');
const jamBerangkat = document.getElementById('jam_berangkat');
const tanggalKembali = document.getElementById('tanggal_kembali');
const jamKembali = document.getElementById('jam_kembali');

function cekKendaraan()
{
    if(
        !tanggalPinjam.value ||
        !jamBerangkat.value ||
        !tanggalKembali.value ||
        !jamKembali.value
    ){
        return;
    }

    const formData = new FormData();

    formData.append('tanggal_pinjam', tanggalPinjam.value);
    formData.append('jam_berangkat', jamBerangkat.value);
    formData.append('tanggal_kembali', tanggalKembali.value);
    formData.append('jam_kembali', jamKembali.value);

    fetch('proses/cek_kendaraan.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(response => {

        kendaraanSelect.innerHTML =
            '<option value="">Pilih Kendaraan</option>';

        response.kendaraan.forEach(k => {

            let option = document.createElement('option');

            option.value = k.no_polisi;
            option.dataset.jenis = k.jenis;

            let text =
                k.merk_jenis + ' - ' + k.no_polisi;

            if(k.status_kendaraan !== 'Tersedia')
            {
                text += ' (' + k.status_kendaraan + ')';
                option.disabled = true;
            }

            option.textContent = text;

            kendaraanSelect.appendChild(option);
        });

        pengemudiSelect.innerHTML =
            '<option value="">Pilih Pengemudi</option>';

        response.pengemudi.forEach(s => {

            let option = document.createElement('option');

            option.value = s.nama_supir;
            option.dataset.polisi = s.no_polisi;

            let text = s.nama_supir;

            if(s.status_supir !== 'Tersedia')
            {
                text += ' (' + s.status_supir + ')';
                option.disabled = true;
            }

            option.textContent = text;

            pengemudiSelect.appendChild(option);
        });

        jenisSelect.dispatchEvent(new Event('change'));
        kendaraanSelect.dispatchEvent(new Event('change'));
    });
}

tanggalPinjam.addEventListener('change', cekKendaraan);
jamBerangkat.addEventListener('change', cekKendaraan);
tanggalKembali.addEventListener('change', cekKendaraan);
jamKembali.addEventListener('change', cekKendaraan);

</script>

</body>
</html>