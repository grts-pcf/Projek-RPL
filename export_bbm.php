<?php
require_once "config/koneksi.php";

header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Pengisian_BBM.xls");

$tglAwal = $_GET['tglAwal'] ?? '';
$tglAkhir = $_GET['tglAkhir'] ?? '';

$where = "";

if($tglAwal != '' && $tglAkhir != ''){
    $where =
        "WHERE p.tanggal_pengisian
         BETWEEN '$tglAwal'
         AND '$tglAkhir'";
}

$query = mysqli_query($conn,"
    SELECT
        p.*,
        k.no_polisi
    FROM pengisian_bbm p
    LEFT JOIN kendaraan k
        ON p.id_kendaraan = k.id_kendaraan
    $where
    ORDER BY p.tanggal_pengisian DESC
");
?>

<table border="1">
<tr>
    <th>No</th>
    <th>No Polisi</th>
    <th>Tanggal</th>
    <th>KM Sebelum</th>
    <th>KM Sesudah</th>
    <th>KM Terpakai</th>
    <th>Jumlah Pengisian</th>
</tr>

<?php
$no=1;
while($d = mysqli_fetch_assoc($query)):
?>

<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['no_polisi'] ?></td>
    <td><?= $d['tanggal_pengisian'] ?></td>
    <td><?= $d['km_sebelum'] ?></td>
    <td><?= $d['km_sesudah'] ?></td>
    <td><?= $d['km_terpakai'] ?></td>
    <td><?= $d['jumlah_pengisian'] ?></td>
</tr>

<?php endwhile; ?>
</table>