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
                   <input type="text" id="searchPengemudi" placeholder="Masukkan nama pengemudi atau no polisi">
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
                                    data-nama="<?= htmlspecialchars($row['nama_supir']); ?>"
                                    data-polisi="<?= htmlspecialchars($row['no_polisi']); ?>"
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
                                    data-nama="<?= htmlspecialchars($row['nama_supir']); ?>"
                                    data-polisi="<?= htmlspecialchars($row['no_polisi']); ?>"
                                    style="
                                        width:auto;
                                        padding:8px 15px;
                                        font-size:14px;
                                        background:#f59e0b;
                                    ">
                                    Detail
                                </button>

                                <button
                                    class="btn-1 btnHapus"
                                    data-id="<?= $row['id']; ?>"
                                    data-nama="<?= htmlspecialchars($row['nama_supir']); ?>"
                                    style="
                                        width:auto;
                                        padding:8px 15px;
                                        font-size:14px;
                                        background:#ef4444;
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

<!-- Modal Tambah -->
<div id="modalTambahPengemudi" class="modal">

    <div class="modal-content">

        <span class="close"  data-modal="tambah">&times;</span>

        <h2>Tambah Pengemudi</h2>

        <form action="proses/tambah_supir.php" method="POST">

            <div class="input-group">
                <label>Nama Pengemudi</label>
                <input
                    type="text"
                    name="nama_supir"
                    required
                >
            </div>

            <div class="input-group">
                <label>No Polisi</label>
                <input
                    type="text"
                    name="no_polisi"
                    required
                >
            </div>

            <button
                type="submit"
                name="simpan"
                class="btn-1"
            >
                Simpan
            </button>

        </form>
        
    </div>

</div>

<!-- Modal Edit -->
<div id="modalEditPengemudi" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="edit">&times;</span>

        <h2>Edit Pengemudi</h2>

        <form action="proses/update_supir.php" method="POST">

            <input
                type="hidden"
                id="edit_id"
                name="id"
            >

            <div class="input-group">
                <label>Nama Pengemudi</label>
                <input
                    type="text"
                    id="edit_nama"
                    name="nama_supir"
                    required
                >
            </div>

            <div class="input-group">
                <label>No Polisi</label>
                <input
                    type="text"
                    id="edit_polisi"
                    name="no_polisi"
                    required
                >
            </div>

            <button
                type="submit"
                class="btn-1"
            >
                Simpan Perubahan
            </button>

        </form>

    </div>

</div>

<!-- Modal Detail -->
<div id="modalDetailPengemudi" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="detail">&times;</span>

        <h2>Detail Pengemudi</h2>

        <div class="detail-container">

            <p>
                <strong>ID :</strong>
                <span id="detail_id"></span>
            </p>

            <p>
                <strong>Nama Pengemudi :</strong>
                <span id="detail_nama"></span>
            </p>

            <p>
                <strong>No Polisi :</strong>
                <span id="detail_polisi"></span>
            </p>

        </div>

    </div>

</div>

<!-- Modal Hapus -->
<div id="modalHapusPengemudi" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="hapus">&times;</span>

        <h2>Hapus Pengemudi</h2>

        <p>
            Yakin ingin menghapus pengemudi
            <strong id="hapus_nama"></strong>?
        </p>

        <br>

        <div style="
            display:flex;
            gap:10px;
            justify-content:center;
        ">

            <a
                id="btnKonfirmasiHapus"
                href="#"
                class="btn-1"
                style="
                    background:#ef4444;
                    text-decoration:none;
                    text-align: center;
                ">
                Ya, Hapus
            </a>

            <button
                type="button"
                id="btnBatalHapus"
                class="btn-1"
                style="
                    background:#6b7280;
                    text-align: center;
                ">
                Batal
            </button>

        </div>

    </div>

</div>

<script>

const searchPengemudi =
document.getElementById('searchPengemudi');

searchPengemudi.addEventListener('keyup', function(){

    const keyword =
    this.value.toLowerCase();

    const rows =
    document.querySelectorAll('#dataPengemudi tr');

    rows.forEach(row => {

        const nama =
        row.cells[1].textContent.toLowerCase();

        const polisi =
        row.cells[2].textContent.toLowerCase();

        const ditemukan =
            nama.includes(keyword) ||
            polisi.includes(keyword);

        row.style.display =
            ditemukan ? '' : 'none';

    });

});

const modalTambah =
document.getElementById('modalTambahPengemudi');

document
.getElementById('btnTambahPengemudi')
.addEventListener('click', function(){

    modalTambah.style.display =
    'block';

});

const modalEdit =
document.getElementById('modalEditPengemudi');

document.querySelectorAll('.btnEdit')
.forEach(btn => {

    btn.addEventListener('click', function(){

        document.getElementById('edit_id').value =
            this.dataset.id;

        document.getElementById('edit_nama').value =
            this.dataset.nama;

        document.getElementById('edit_polisi').value =
            this.dataset.polisi;

        modalEdit.style.display =
            'block';

    });

});

const modalDetail =
document.getElementById('modalDetailPengemudi');

document.querySelectorAll('.btnDetail')
.forEach(btn => {

    btn.addEventListener('click', function(){

        document.getElementById('detail_id')
        .textContent = this.dataset.id;

        document.getElementById('detail_nama')
        .textContent = this.dataset.nama;

        document.getElementById('detail_polisi')
        .textContent = this.dataset.polisi;

        modalDetail.style.display =
        'block';

    });

});

const modalHapus =
document.getElementById('modalHapusPengemudi');

const btnKonfirmasiHapus =
document.getElementById('btnKonfirmasiHapus');

document.querySelectorAll('.btnHapus')
.forEach(btn => {

    btn.addEventListener('click', function(){

        document.getElementById('hapus_nama')
        .textContent = this.dataset.nama;

        btnKonfirmasiHapus.href =
            'proses/hapus_supir.php?id=' +
            this.dataset.id;

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

        if (this.dataset.modal === 'tambah') {
            document.getElementById('modalTambahPengemudi')
            .style.display = 'none';
        }

        if (this.dataset.modal === 'edit') {
            modalEdit.style.display = 'none';
        }

        if (this.dataset.modal === 'detail') {
            modalDetail.style.display = 'none';
        }

        if (this.dataset.modal === 'hapus') {
            modalHapus.style.display = 'none';
        }

    });

});
</script>

</body>
</html>