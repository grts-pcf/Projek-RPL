<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";

$query = mysqli_query($conn, "
    SELECT *
    FROM data_peminjam
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjam</title>

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

            <li class="active">
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

            <h1>Data Peminjam</h1>

            <div class="profile">
                <a href="LOGIN.html">Admin</a>
            </div>

        </div>

        <!-- Header Action -->
        <div class="table-container">

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:20px;
            ">

                <h2>Daftar Data Peminjam</h2>

            </div>

            <!-- Search -->
            <div class="input-group">

                <input
                type="text"
                id="searchInput"
                placeholder="Cari nama peminjam..."
>

        </div>

            <!-- Table -->
            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Unit</th>
                        <th>No Telepon</th>
                        <th>Riwayat Terakhir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody id="dataPeminjam">

                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query)) :
                    ?>

                    <?php

                    $nama = $row['nama_peminjam'];

                    $qRiwayat = mysqli_query($conn,"
                        SELECT tanggal_kembali, jam_kembali
                        FROM riwayat
                        WHERE nama_peminjam = '$nama'
                        ORDER BY id DESC
                        LIMIT 1
                    ");

                    $riwayat = mysqli_fetch_assoc($qRiwayat);

                    if($riwayat){

                        $batas_kembali = strtotime(
                            $riwayat['tanggal_kembali'] . ' ' .
                            $riwayat['jam_kembali']
                        );

                        $status = (time() <= $batas_kembali)
                            ? 'aktif'
                            : 'nonaktif';

                    }else{

                        $status = 'nonaktif';

                    }
                    ?>

                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nama_peminjam']; ?></td>
                        <td><?= $row['unit']; ?></td>
                        <td><?= $row['no_telepon']; ?></td>

                        <td>
                            <?php if ($riwayat) : ?>
                                <?= date('d F Y', strtotime($riwayat['tanggal_kembali'])) ?>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($status == 'aktif') : ?>
                                <span class="success">
                                    Aktif
                                </span>
                            <?php else : ?>
                                <span class="pending">
                                    Nonaktif
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="action-buttons">

                                <button style="
                                    width:auto;
                                    padding:8px 15px;
                                    font-size:14px;
                                    "class = "btn-1 btnEdit"
                                    data-id="<?= $row['id']; ?>"
                                    data-nama="<?= $row['nama_peminjam']; ?>"
                                    data-unit="<?= $row['unit']; ?>"
                                    data-telp="<?= $row['no_telepon']; ?>">
                                    Edit
                                </button>

                                <button 
                                    style="
                                        width:auto;
                                        padding:8px 15px;
                                        font-size:14px;
                                        background:#ef4444;"
                                    class="btn-1 btnHapusPeminjam"
                                    data-id="<?= $row['id']; ?>"
                                    data-nama="<?= $row['nama_peminjam']; ?>"
                                >
                                    Hapus
                                </button>

                            </div>
                        </td>
                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

            <div id="modalEdit" class="modal">

            <div class="modal-content">

                <span class="close" data-modal="edit">&times;</span>

                <h2>Edit Data Peminjam</h2>

                <form action="proses/update_peminjam.php" method="POST">

                    <input type="hidden" id="edit_id" name="id">

                    <div class="form-grid">

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" id="edit_nama" name="nama_peminjam">
                        </div>

                        <div class="form-group">
                            <label>Unit</label>
                            <input type="text" id="edit_unit" name="unit">
                        </div>

                        <div class="form-group">
                            <label>No Telepon</label>
                            <input type="text" id="edit_telp" name="no_telepon">
                        </div>

                    </div>

                    <div class="form-action">
                        <button type="submit" class="btn-2">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>

        </div>

        </div>

    </div>

</div>

<!-- Modal Hapus Peminjam -->
<div id="modalHapusPeminjam" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="hapuspeminjam">&times;</span>

        <h2>Hapus Data Peminjam</h2>

        <p>
            Yakin ingin menghapus data
            <strong id="namaPeminjamHapus"></strong>?
        </p>

        <br>

        <div style="display:flex; gap:10px;">

            <a
                id="btnKonfirmasiHapusPeminjam"
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
                id="btnBatalHapusPeminjam"
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

const modal = document.getElementById('modalEdit');

document.querySelectorAll('.btnEdit').forEach(btn=>{

    btn.addEventListener('click',function(){

        document.getElementById('edit_id').value =
            this.dataset.id;

        document.getElementById('edit_nama').value =
            this.dataset.nama;

        document.getElementById('edit_unit').value =
            this.dataset.unit;

        document.getElementById('edit_telp').value =
            this.dataset.telp;

        modal.style.display = 'block';

    });

});

window.onclick = function(event){

    if(event.target == modal){

        modal.style.display='none';

    }

}

const modalHapusPeminjam =
document.getElementById('modalHapusPeminjam');

const namaPeminjamHapus =
document.getElementById('namaPeminjamHapus');

const btnKonfirmasiHapusPeminjam =
document.getElementById('btnKonfirmasiHapusPeminjam');

document
.querySelectorAll('.btnHapusPeminjam')
.forEach(btn => {

    btn.addEventListener('click', function(){

        const id = this.dataset.id;

        namaPeminjamHapus.textContent =
        this.dataset.nama;

        btnKonfirmasiHapusPeminjam.href =
        'proses/hapus_peminjam.php?id=' + id;

        modalHapusPeminjam.style.display =
        'block';

    });

});

document
.getElementById('btnBatalHapusPeminjam')
.addEventListener('click', function(){

    modalHapusPeminjam.style.display =
    'none';

});

document.querySelector('.close')
.addEventListener('click',function(){

    modalEdit.style.display='none';
    modalHapusPeminjam.style.display =
    'none';

});

</script>

</body>
</html>