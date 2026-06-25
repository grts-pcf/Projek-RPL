<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";

// ===========================
// Jadwal Ganti Oli
// ===========================
$where = [];

if(!empty($_GET['tanggal_service'])){
    $tanggal =
        mysqli_real_escape_string(
            $conn,
            $_GET['tanggal_service']
        );

    $where[] =
        "j.tanggal_service='$tanggal'";
}

if(!empty($_GET['status'])){
    $status =
        mysqli_real_escape_string(
            $conn,
            $_GET['status']
        );

    $where[] =
        "j.status='$status'";
}

if(!empty($_GET['kendaraan'])){
    $kendaraan =
        mysqli_real_escape_string(
            $conn,
            $_GET['kendaraan']
        );

    $where[] =
        "(
            k.no_polisi LIKE '%$kendaraan%'
            OR
            k.merk_jenis LIKE '%$kendaraan%'
        )";
}

$sqlJadwal = "
SELECT
    j.*,
    k.no_polisi,
    k.merk_jenis
FROM jadwal_ganti_oli_kendaraan_operasional j
LEFT JOIN kendaraan k
ON j.id_kendaraan = k.id_kendaraan
";

if(count($where)>0){
    $sqlJadwal .=
        " WHERE ".implode(" AND ",$where);
}

$sqlJadwal .= "
ORDER BY
j.tanggal_service DESC
";

$queryJadwal =
mysqli_query($conn,$sqlJadwal);

// ===========================
// Pengisian BBM
// ===========================
$queryBBM = mysqli_query($conn,"
SELECT
    p.*,
    k.no_polisi,
    k.merk_jenis
FROM pengisian_bbm p
LEFT JOIN kendaraan k
ON p.id_kendaraan = k.id_kendaraan
ORDER BY p.tanggal_pengisian DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Maintenance</title>

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
                class="logo-img">

            <h2>Transportasi UBD</h2>

        </div>

        <ul class="menu">

            <li><a href="index.php">Dashboard</a></li>

            <li><a href="data-peminjam.php">Data Peminjam</a></li>

            <li><a href="riwayat.php">Riwayat Peminjaman</a></li>

            <li><a href="kendaraan.php">Master Kendaraan</a></li>

            <li><a href="pengemudi.php">Pengemudi</a></li>

            <li class="active"><a href="jadwal.php">Jadwal Maintenance</a></li>

            <li><a href="laporan.php">Laporan</a></li>

            <li><a href="pengaturan.php">Pengaturan</a></li>

            <li class="logout-menu">
                <a href="proses/logout.php">Logout</a>
            </li>

        </ul>

    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Navbar -->
        <div class="navbar">

            <h1>Jadwal Maintenance</h1>

            <div class="profile">

                <a href="#">Admin</a>

            </div>

        </div>

        <!-- Cards -->
        <div class="cards">

            <div class="card">
                <h3>Sedang Maintenance</h3>
                <p>6</p>
            </div>

            <div class="card">
                <h3>Jadwal Hari Ini</h3>
                <p>3</p>
            </div>

            <div class="card">
                <h3>Maintenance Selesai</h3>
                <p>12</p>
            </div>

            <div class="card">
                <h3>Pengisian BBM</h3>
                <p>48</p>
            </div>

        </div>

        <!-- Filter Jadwal Ganti Oli -->

        <div class="form-container">

            <h2>Filter Jadwal Ganti Oli</h2>

            <div class="row">

                <div class="input-group">
                    <label>Tanggal Service</label>
                    <input type="date" id="filterTanggal">
                </div>

                <div class="input-group">
                    <label>Status</label>
                    <select id="filterStatus">
                        <option value="Semua">Semua</option>
                        <option value="Proses">Proses</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Kendaraan</label>
                    <input
                        type="text"
                        id="filterKendaraan"
                        placeholder="Cari kendaraan...">
                </div>

            </div>

            <button
                type="button"
                id="btnFilterJadwal"
                class="btn-1">
                Cari
            </button>

        </div>

        <!-- ====================================== -->
        <!-- TABLE JADWAL GANTI OLI -->
        <!-- ====================================== -->

        <div class="table-container">

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:20px;
            ">

                <h2>Jadwal Ganti Oli Kendaraan</h2>

                <button id="btnTambahJadwal" class="btn-1"
                    style="
                    width:auto;
                    padding:12px 20px;
                    ">

                    + Tambah Jadwal

                </button>

            </div>

            <table>

                <thead>

                    <tr
                        data-tanggal="<?= $row['tanggal_service']; ?>"
                        data-status="<?= strtolower($row['status']); ?>"
                        data-kendaraan="<?= strtolower($row['no_polisi'].' '.$row['merk_jenis']); ?>"
                    >

                        <th>No</th>

                        <th>No Polisi</th>

                        <th>Kendaraan</th>

                        <th>Tanggal Service</th>

                        <th>KM Ganti Oli</th>

                        <th>KM Selanjutnya</th>

                        <th>Status</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody id="dataJadwal">

                <?php
                $no = 1;

                while($row = mysqli_fetch_assoc($queryJadwal))
                {
                    $status = $row['status'];
                ?>

                <tr
                    data-tanggal="<?= $row['tanggal_service']; ?>"
                    data-status="<?= strtolower($row['status']); ?>"
                    data-kendaraan="<?= strtolower($row['no_polisi'].' '.$row['merk_jenis']); ?>"
                >

                    <td><?= $no++; ?></td>

                    <td><?= $row['no_polisi']; ?></td>

                    <td><?= $row['merk_jenis']; ?></td>

                    <td><?= date('d M Y', strtotime($row['tanggal_service'])); ?></td>

                    <td><?= number_format($row['km_ganti_oli'],0,',','.'); ?></td>

                    <td><?= number_format($row['km_ganti_oli_selanjutnya'],0,',','.'); ?></td>

                    <td>

                    <?php
                    $class = "pending";

                    if($status=="Selesai"){
                        $class = "success";
                    }

                    if($status=="Maintenance"){
                        $class = "maintenance";
                    }
                    ?>

                    <span class="<?= $class ?>">
                        <?= $status ?>
                    </span>

                    </td>

                    <td>

                        <button 
                            style="
                            width:auto;
                            padding:8px 15px;
                            font-size:14px;
                            "class="btn-1 btnEdit"
                            data-id="<?= $row['id']; ?>"
                            data-kendaraan="<?= $row['id_kendaraan']; ?>"
                            data-tanggal_service="<?= $row['tanggal_service']; ?>"
                            data-tanggal_spk="<?= $row['tanggal_spk']; ?>"
                            data-no_spk="<?= $row['no_spk']; ?>"
                            data-tanggal_lpj="<?= $row['tanggal_lpj']; ?>"
                            data-no_lpj="<?= $row['no_lpj']; ?>"
                            data-km="<?= $row['km_ganti_oli']; ?>"
                            data-kmnext="<?= $row['km_ganti_oli_selanjutnya']; ?>"
                            data-keterangan="<?= $row['keterangan']; ?>">
                            Edit</button>

                        <button 
                            style="
                            width:auto;
                            padding:8px 15px;
                            font-size:14px;
                            background:#f59e0b;
                            "class="btn-1 btnReview"
                            data-id="<?= $row['id']; ?>"
                            data-nopol="<?= $row['no_polisi']; ?>"
                            data-kendaraan="<?= $row['merk_jenis']; ?>"
                            data-tanggal="<?= $row['tanggal_service']; ?>"
                            data-km="<?= $row['km_ganti_oli']; ?>"
                            data-kmnext="<?= $row['km_ganti_oli_selanjutnya']; ?>"
                            data-status="<?= $row['status']; ?>">
                            Review
                        </button>

                        <button 
                            style="
                            width:auto;
                            padding:8px 15px;
                            font-size:14px;
                            background:#ef4444;
                            "class="btn-1 btnHapusJadwal"
                            data-id="<?= $row['id']; ?>"
                            data-kendaraan="<?= $row['no_polisi']; ?> - <?= $row['merk_jenis']; ?>">
                            Hapus
                        </button>

                    </td>

                </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>
        
        <!-- Filter Pengisian BBM -->
        <div class="form-container" style="margin-top:35px;">

            <h2>Filter Pengisian BBM</h2>

            <div class="row">

                <div class="input-group">

                    <label>Tanggal Awal</label>

                    <input
                        type="date"
                        id="filterBBMAwal">

                </div>

                <div class="input-group">

                    <label>Tanggal Akhir</label>

                    <input
                        type="date"
                        id="filterBBMAkhir">

                </div>

                <div class="input-group">

                    <label>Kendaraan</label>

                    <input
                        type="text"
                        id="filterBBMKendaraan"
                        placeholder="Cari Kendaraan">

                </div>

            </div>

            <button 
                class="btn-1" 
                id="btnCariBBM">
                Cari

            </button>

        </div>

        <!-- ====================================== -->
        <!-- TABLE PENGISIAN BBM -->
        <!-- ====================================== -->

        <div class="table-container" style="margin-top:35px;">

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:20px;
            ">

                <h2>Riwayat Pengisian BBM</h2>

                <button id="btnTambahBBM" class="btn-1"
                    style="
                    width:auto;
                    padding:12px 20px;
                    ">

                    + Tambah Laporan Pengisian

                </button>

            </div>

            <table>

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Tanggal</th>

                        <th>No Polisi</th>

                        <th>Kendaraan</th>

                        <th>Jumlah Pengisian (Rp)</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody id="dataBBM">

                <?php

                $no = 1;

                while($row = mysqli_fetch_assoc($queryBBM))
                { 
                
                ?>

                <tr
                    data-tanggal="<?= $row['tanggal_pengisian']; ?>"
                    data-kendaraan="<?= strtolower($row['no_polisi'].' '.$row['merk_jenis']); ?>"
                >

                    <td><?= $no++; ?></td>

                    <td><?= date('d M Y',strtotime($row['tanggal_pengisian'])); ?></td>

                    <td><?= $row['no_polisi']; ?></td>

                    <td><?= $row['merk_jenis']; ?></td>

                    <td>
                        Rp <?= number_format($row['jumlah_pengisian'],0,',','.'); ?>
                    </td>

                    <td>

                        <button 
                            style="
                            width:auto;
                            padding:8px 15px;
                            font-size:14px;
                            "class="btn-1 btnDetail"
                            data-id="<?= $row['id']; ?>"
                            data-tanggal="<?= $row['tanggal_pengisian']; ?>"
                            data-nopol="<?= $row['no_polisi']; ?>"
                            data-kendaraan="<?= $row['merk_jenis']; ?>"
                            data-kmsebelum="<?= $row['km_sebelum']; ?>"
                            data-kmsesudah="<?= $row['km_sesudah']; ?>"
                            data-kmterpakai="<?= $row['km_terpakai']; ?>"
                            data-jumlah="<?= $row['jumlah_pengisian']; ?>">
                            Detail
                        </button>

                        <button
                            style="
                            width:auto;
                            padding:8px 15px;
                            font-size:14px;
                            background:#ef4444;
                            "class="btn-1 btnHapusPengisian"
                            data-kendaraan="<?= $row['no_polisi']; ?> - <?= $row['merk_jenis']; ?>">
                            Hapus
                        </button>

                    </td>

                </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div id="modalTambahJadwal" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="jadwal">&times;</span>

        <h2>Tambah Jadwal Ganti Oli</h2>

        <form action="proses/tambah_jadwal.php" method="POST">

            <div class="form-grid">

                <div class="input-group">
                    <label>Kendaraan</label>

                    <select name="id_kendaraan" required>

                        <?php
                        $kendaraan = mysqli_query($conn,"
                            SELECT id_kendaraan,no_polisi,merk_jenis
                            FROM kendaraan
                            ORDER BY merk_jenis
                        ");

                        while($k=mysqli_fetch_assoc($kendaraan)):
                        ?>

                        <option value="<?= $k['id_kendaraan']; ?>">
                            <?= $k['no_polisi']; ?> - <?= $k['merk_jenis']; ?>
                        </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <div class="input-group">

                    <label>Tanggal Service</label>

                    <input
                        type="date"
                        name="tanggal_service"
                        required>

                </div>

                <div class="input-group">

                    <label>Tanggal SPK</label>

                    <input
                        type="date"
                        name="tanggal_spk">

                </div>

                <div class="input-group">

                    <label>No SPK</label>

                    <input
                        type="text"
                        name="no_spk"
                        placeholder="Masukkan Nomor SPK">

                </div>

                <div class="input-group">

                    <label>Tanggal LPJ</label>

                    <input
                        type="date"
                        name="tanggal_lpj">

                </div>

                <div class="input-group">

                    <label>No LPJ</label>

                    <input
                        type="text"
                        name="no_lpj"
                        placeholder="Masukkan Nomor LPJ">

                </div>

                <div class="input-group">

                    <label>KM Ganti Oli</label>

                    <input
                        type="number"
                        name="km_ganti_oli"
                        required>

                </div>

                <div class="input-group">

                    <label>KM Ganti Oli Selanjutnya</label>

                    <input
                        type="number"
                        name="km_ganti_oli_selanjutnya"
                        required>

                </div>

            </div>

            <div class="input-group" style="margin-top:20px;">

                <label>Keterangan</label>

                <textarea
                    name="keterangan"
                    rows="4"
                    placeholder="Masukkan keterangan"></textarea>

            </div>

            <button
                class="btn-1"
                type="submit">

                Simpan

            </button>

        </form>

    </div>

</div>

<div id="modalTambahBBM" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="bbm">&times;</span>

        <h2>Tambah Pengisian BBM</h2>

        <form action="proses/tambah_bbm.php" method="POST">

            <div class="input-group">

                <label>Kendaraan</label>

                <select name="id_kendaraan" required>

                    <?php
                    mysqli_data_seek($kendaraan,0);

                    while($k=mysqli_fetch_assoc($kendaraan)):
                    ?>

                    <option value="<?= $k['id_kendaraan']; ?>">
                        <?= $k['no_polisi']; ?> -
                        <?= $k['merk_jenis']; ?>
                    </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <div class="input-group">

                <label>Pengemudi</label>

                <select name="id_supir" required>

                    <?php
                    $supir = mysqli_query($conn,"
                        SELECT *
                        FROM supir
                        ORDER BY nama_supir
                    ");

                    while($s = mysqli_fetch_assoc($supir)):
                    ?>

                    <option value="<?= $s['id']; ?>">
                        <?= $s['nama_supir']; ?>
                    </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <div class="input-group">

                <label>Tanggal Pengisian</label>

                <input
                    type="date"
                    name="tanggal_pengisian"
                    required>

            </div>

            <div class="input-group">

                <label>KM Sebelum</label>

                <input
                    type="number"
                    name="km_sebelum"
                    required>

            </div>

            <div class="input-group">

                <label>KM Sesudah</label>

                <input
                    type="number"
                    name="km_sesudah"
                    required>

            </div>

            <div class="input-group">

                <label>Jumlah Pengisian (Rp)</label>

                <input
                    type="number"
                    name="jumlah_pengisian"
                    placeholder="Masukkan nominal pengisian"
                    required>

            </div>

            <button
                class="btn-1"
                type="submit">

                Simpan

            </button>

        </form>

    </div>

</div>

<div id="modalEditJadwal" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="edit">&times;</span>

        <h2>Edit Jadwal Ganti Oli</h2>

        <form action="proses/edit_jadwal.php" method="POST">

            <input type="hidden" name="id" id="edit_id">

            <div class="form-grid">

                <div class="input-group">
                    <label>Kendaraan</label>

                    <select name="id_kendaraan" id="edit_kendaraan">

                        <?php
                        mysqli_data_seek($kendaraan,0);
                        while($k=mysqli_fetch_assoc($kendaraan)):
                        ?>

                        <option value="<?= $k['id_kendaraan']; ?>">
                            <?= $k['no_polisi']; ?> -
                            <?= $k['merk_jenis']; ?>
                        </option>

                        <?php endwhile; ?>

                    </select>
                </div>

                <div class="input-group">
                    <label>Tanggal Service</label>
                    <input type="date"
                           name="tanggal_service"
                           id="edit_tanggal_service">
                </div>

                <div class="input-group">
                    <label>Tanggal SPK</label>
                    <input type="date"
                           name="tanggal_spk"
                           id="edit_tanggal_spk">
                </div>

                <div class="input-group">
                    <label>No SPK</label>
                    <input type="text"
                           name="no_spk"
                           id="edit_no_spk">
                </div>

                <div class="input-group">
                    <label>Tanggal LPJ</label>
                    <input type="date"
                           name="tanggal_lpj"
                           id="edit_tanggal_lpj">
                </div>

                <div class="input-group">
                    <label>No LPJ</label>
                    <input type="text"
                           name="no_lpj"
                           id="edit_no_lpj">
                </div>

                <div class="input-group">
                    <label>KM Ganti Oli</label>
                    <input type="number"
                           name="km_ganti_oli"
                           id="edit_km">
                </div>

                <div class="input-group">
                    <label>KM Selanjutnya</label>
                    <input type="number"
                           name="km_ganti_oli_selanjutnya"
                           id="edit_kmnext">
                </div>

            </div>

            <div class="input-group" style="margin-top:20px;">
                <label>Keterangan</label>

                <textarea
                    name="keterangan"
                    id="edit_keterangan"></textarea>
            </div>

            <button class="btn-1" type="submit">
                Update
            </button>

        </form>

    </div>

</div>

<div id="modalReview" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="review">&times;</span>

        <h2>Review Jadwal Maintenance</h2>

        <div class="detail-container">

            <div class="detail-row">
                <span>No Polisi</span>
                <strong id="r_nopol"></strong>
            </div>

            <div class="detail-row">
                <span>Kendaraan</span>
                <strong id="r_kendaraan"></strong>
            </div>

            <div class="detail-row">
                <span>Tanggal Service</span>
                <strong id="r_tanggal"></strong>
            </div>

            <div class="detail-row">
                <span>KM Ganti Oli</span>
                <strong id="r_km"></strong>
            </div>

            <div class="detail-row">
                <span>KM Selanjutnya</span>
                <strong id="r_kmnext"></strong>
            </div>

        </div>

        <form action="proses/update_status_jadwal.php" method="POST">

            <input type="hidden" name="id" id="r_id">

            <div class="input-group" style="margin-top:20px;">

                <label>Status</label>

                <select name="status" id="r_status">

                    <option value="Proses">
                        Proses
                    </option>

                    <option value="Maintenance">
                        Maintenance
                    </option>

                    <option value="Selesai">
                        Selesai
                    </option>

                </select>

            </div>

            <button
                class="btn-1"
                style="margin-top:20px;">
                Update Status
            </button>

        </form>

    </div>

</div>

<div id="modalHapusJadwal" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="hapusJadwal">&times;</span>

        <h2>Hapus Jadwal Ganti Oli</h2>

        <p>
            Yakin ingin menghapus jadwal kendaraan
            <strong id="hapus_kendaraan"></strong>?
        </p>

        <br>

        <div style="
            display:flex;
            gap:10px;
            justify-content:center;
        ">

            <a
                id="btnKonfirmasiHapusJadwal"
                href="#"
                class="btn-1"
                style="
                    text-align: center;
                    background:#ef4444;
                    text-decoration:none;
                ">
                Ya, Hapus
            </a>

            <button
                type="button"
                id="btnBatalHapusJadwal"
                class="btn-1"
                style="
                    text-align: center;
                    background:#6b7280;
                ">
                Batal
            </button>

        </div>

    </div>

</div>

<div id="modalDetailBBM" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="detailBBM">
            &times;
        </span>

        <h2>Detail Pengisian BBM</h2>

        <div class="detail-container">

            <div class="detail-row">
                <span>Tanggal Pengisian</span>
                <strong id="d_tanggal"></strong>
            </div>

            <div class="detail-row">
                <span>No Polisi</span>
                <strong id="d_nopol"></strong>
            </div>

            <div class="detail-row">
                <span>Kendaraan</span>
                <strong id="d_kendaraan"></strong>
            </div>

            <div class="detail-row">
                <span>KM Sebelum</span>
                <strong id="d_kmsebelum"></strong>
            </div>

            <div class="detail-row">
                <span>KM Sesudah</span>
                <strong id="d_kmsesudah"></strong>
            </div>

            <div class="detail-row">
                <span>KM Terpakai</span>
                <strong id="d_kmterpakai"></strong>
            </div>

            <div class="detail-row">
                <span>Jumlah Pengisian</span>
                <strong id="d_jumlah"></strong>
            </div>

        </div>

    </div>

</div>

<div id="modalHapusBBM" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="hapusBBM">
            &times;
        </span>

        <h2>Hapus Data Pengisian BBM</h2>

        <p>
            Yakin ingin menghapus data pengisian BBM kendaraan
            <strong id="hapus_bbm_kendaraan"></strong> ?
        </p>

        <br>

        <div style="
            display:flex;
            gap:10px;
            justify-content:center;
        ">

            <a
                id="btnKonfirmasiHapusBBM"
                href="#"
                class="btn-1"
                style="
                    text-align: center;
                    background:#ef4444;
                    text-decoration:none;
                ">
                Ya, Hapus
            </a>

            <button
                type="button"
                id="btnBatalHapusBBM"
                class="btn-1"
                style="
                    text-align: center;
                    background:#6b7280;
                ">
                Batal
            </button>

        </div>

    </div>

</div>

<script>

document.getElementById("btnFilterJadwal")
.addEventListener("click", function(){

    const tanggal =
        document.getElementById("filterTanggal")
        .value;

    const status =
        document.getElementById("filterStatus")
        .value
        .toLowerCase();

    const kendaraan =
        document.getElementById("filterKendaraan")
        .value
        .toLowerCase();

    const rows =
        document.querySelectorAll(
            "#dataJadwal tr"
        );

    rows.forEach(function(row){

        const tanggalData =
            row.dataset.tanggal;

        const statusData =
            row.dataset.status;

        const kendaraanData =
            row.dataset.kendaraan;

        const cocokTanggal =
            tanggal === '' ||
            tanggalData === tanggal;

        const cocokStatus =
            status === 'semua' ||
            statusData === status;

        const cocokKendaraan =
            kendaraan === '' ||
            kendaraanData.includes(kendaraan);

        row.style.display =
            (
                cocokTanggal &&
                cocokStatus &&
                cocokKendaraan
            )
            ? ''
            : 'none';

    });

});

document.getElementById("btnCariBBM")
.addEventListener("click", function(){

    const tglAwal =
        document.getElementById("filterBBMAwal")
        .value;

    const tglAkhir =
        document.getElementById("filterBBMAkhir")
        .value;

    const kendaraan =
        document.getElementById("filterBBMKendaraan")
        .value
        .toLowerCase();

    const rows =
        document.querySelectorAll(
            "#dataBBM tr"
        );

    rows.forEach(function(row){

        const tanggalData =
            row.dataset.tanggal;

        const kendaraanData =
            row.dataset.kendaraan;

        let cocokTanggal = true;

        if(
            tglAwal !== '' &&
            tanggalData < tglAwal
        ){
            cocokTanggal = false;
        }

        if(
            tglAkhir !== '' &&
            tanggalData > tglAkhir
        ){
            cocokTanggal = false;
        }

        const cocokKendaraan =
            kendaraan === '' ||
            kendaraanData.includes(kendaraan);

        row.style.display =
            (
                cocokTanggal &&
                cocokKendaraan
            )
            ? ''
            : 'none';

    });

});

const modalTambahJadwal =
document.getElementById("modalTambahJadwal");

document
.getElementById("btnTambahJadwal")
.addEventListener("click",function(){

    modalTambahJadwal.style.display = "block";
    modalTambahJadwal.scrollTop = 0;
    document.body.classList.add("modal-open");

});

const modalEdit =
document.getElementById("modalEditJadwal");

document.querySelectorAll(".btnEdit")
.forEach(btn => {

    btn.addEventListener("click", function(){

        document.getElementById("edit_id").value =
            this.dataset.id;

        document.getElementById("edit_kendaraan").value =
            this.dataset.kendaraan;

        document.getElementById("edit_tanggal_service").value =
            this.dataset.tanggal_service;

        document.getElementById("edit_tanggal_spk").value =
            this.dataset.tanggal_spk;

        document.getElementById("edit_no_spk").value =
            this.dataset.no_spk;

        document.getElementById("edit_tanggal_lpj").value =
            this.dataset.tanggal_lpj;

        document.getElementById("edit_no_lpj").value =
            this.dataset.no_lpj;

        document.getElementById("edit_km").value =
            this.dataset.km;

        document.getElementById("edit_kmnext").value =
            this.dataset.kmnext;

        document.getElementById("edit_keterangan").value =
            this.dataset.keterangan;

        modalEdit.style.display = "block";
        document.body.classList.add("modal-open");

    });

});

const modalTambahBBM =
document.getElementById("modalTambahBBM");

document
.getElementById("btnTambahBBM")
.addEventListener("click",function(){

    modalTambahBBM.style.display="block";
    document.body.classList.add("modal-open");

});

const modalReview =
document.getElementById("modalReview");

document.querySelectorAll(".btnReview")
.forEach(btn=>{

    btn.addEventListener("click",function(){

        document.getElementById("r_id").value =
            this.dataset.id;

        document.getElementById("r_nopol").innerHTML =
            this.dataset.nopol;

        document.getElementById("r_kendaraan").innerHTML =
            this.dataset.kendaraan;

        document.getElementById("r_tanggal").innerHTML =
            this.dataset.tanggal;

        document.getElementById("r_km").innerHTML =
            this.dataset.km;

        document.getElementById("r_kmnext").innerHTML =
            this.dataset.kmnext;

        document.getElementById("r_status").value =
            this.dataset.status;

        modalReview.style.display = "block";
        document.body.classList.add("modal-open");

    });

});

const modalHapusJadwal =
document.getElementById("modalHapusJadwal");

const btnKonfirmasiHapusJadwal =
document.getElementById(
    "btnKonfirmasiHapusJadwal"
);

document.querySelectorAll(".btnHapusJadwal")
.forEach(btn => {

    btn.addEventListener("click", function(){

        document.getElementById(
            "hapus_kendaraan"
        ).textContent =
            this.dataset.kendaraan;

        btnKonfirmasiHapusJadwal.href =
            "proses/hapus_jadwal.php?id=" +
            this.dataset.id;

        modalHapusJadwal.style.display =
            "block";

        document.body.classList.add(
            "modal-open"
        );

    });

});

document
.getElementById("btnBatalHapusJadwal")
.addEventListener("click", function(){

    modalHapusJadwal.style.display =
        "none";

    document.body.classList.remove(
        "modal-open"
    );

});

const modalDetailBBM =
document.getElementById("modalDetailBBM");

document.querySelectorAll(".btnDetail")
.forEach(btn => {

    btn.addEventListener("click", function(){

        document.getElementById("d_tanggal")
            .innerHTML = this.dataset.tanggal;

        document.getElementById("d_nopol")
            .innerHTML = this.dataset.nopol;

        document.getElementById("d_kendaraan")
            .innerHTML = this.dataset.kendaraan;

        document.getElementById("d_kmsebelum")
            .innerHTML =
            Number(this.dataset.kmsebelum)
            .toLocaleString('id-ID') + " KM";

        document.getElementById("d_kmsesudah")
            .innerHTML =
            Number(this.dataset.kmsesudah)
            .toLocaleString('id-ID') + " KM";

        document.getElementById("d_kmterpakai")
            .innerHTML =
            Number(this.dataset.kmterpakai)
            .toLocaleString('id-ID') + " KM";

        document.getElementById("d_jumlah")
            .innerHTML =
            "Rp " +
            Number(this.dataset.jumlah)
            .toLocaleString('id-ID');

        modalDetailBBM.style.display =
            "block";

        document.body.classList.add(
            "modal-open"
        );

    });

});

const modalHapusBBM =
document.getElementById("modalHapusBBM");

const btnKonfirmasiHapusBBM =
document.getElementById(
    "btnKonfirmasiHapusBBM"
);

document.querySelectorAll(".btnHapusPengisian")
.forEach(btn => {

    btn.addEventListener("click", function(){

        document.getElementById(
            "hapus_bbm_kendaraan"
        ).textContent =
            this.dataset.kendaraan;

        btnKonfirmasiHapusBBM.href =
            "proses/hapus_bbm.php?id=" +
            this.dataset.id;

        modalHapusBBM.style.display =
            "block";

        document.body.classList.add(
            "modal-open"
        );

    });

});

document
.getElementById("btnBatalHapusBBM")
.addEventListener("click", function(){

    modalHapusBBM.style.display =
        "none";

    document.body.classList.remove(
        "modal-open"
    );

});

document.querySelectorAll(".close").forEach(btn=>{

    btn.addEventListener("click",function(){

        if(this.dataset.modal=="jadwal"){
            modalTambahJadwal.style.display="none";
        }

        if(this.dataset.modal=="bbm"){
            modalTambahBBM.style.display="none";
        }

        if(this.dataset.modal=="edit"){
            modalEdit.style.display="none";
        }

        if(this.dataset.modal=="review"){
            modalReview.style.display="none";
        }

        if(this.dataset.modal=="hapusJadwal"){
            modalHapusJadwal.style.display ="none";
        }

        if(this.dataset.modal=="detailBBM"){
            modalDetailBBM.style.display = "none";
        }

        if(this.dataset.modal=="hapusBBM"){
            modalHapusBBM.style.display = "none";
        }

        document.body.classList.remove("modal-open");
        
    });

});

</script>
</body>
</html>