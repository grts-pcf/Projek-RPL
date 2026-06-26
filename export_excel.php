<?php

require_once "config/koneksi.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Peminjaman.xls");

$tglAwal = $_GET['tglAwal'] ?? '';
$tglAkhir = $_GET['tglAkhir'] ?? '';
$status = strtolower($_GET['status'] ?? 'semua');

$sql = "
SELECT
    r.*,
    k.merk_jenis
FROM riwayat r
LEFT JOIN kendaraan k
    ON r.kendaraan = k.no_polisi
WHERE 1=1
";

if($status != 'semua' && $status != ''){
    $sql .= " AND LOWER(r.status) = '$status'";
}

if($tglAwal != '' && $tglAkhir != ''){

    $sql .= "
    AND r.tanggal_pinjam <= '$tglAkhir'
    AND r.tanggal_kembali >= '$tglAwal'
    ";
}

$sql .= " ORDER BY r.id DESC";

$query = mysqli_query($conn, $sql);

?>

<table border="1">

    <tr>
        <th>No</th>
        <th>Nama Peminjam</th>
        <th>Kendaraan</th>
        <th>Pengemudi</th>
        <th>Tanggal Pinjam</th>
        <th>Tanggal Kembali</th>
        <th>Tujuan</th>
        <th>Status</th>
    </tr>

    <?php
    $no = 1;

    while($r = mysqli_fetch_assoc($query)):
    ?>

    <tr>
        <td><?= $no++; ?></td>
        <td><?= $r['nama_peminjam']; ?></td>
        <td><?= $r['merk_jenis']; ?></td>
        <td><?= $r['pengemudi']; ?></td>
        <td><?= $r['tanggal_pinjam']; ?></td>
        <td><?= $r['tanggal_kembali']; ?></td>
        <td><?= $r['tujuan']; ?></td>
        <td><?= ucfirst($r['status']); ?></td>
    </tr>

    <?php endwhile; ?>

</table>