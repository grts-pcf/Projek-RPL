<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: LOGIN.php");
    exit();
}
require_once "config/koneksi.php";

$queryAdmin = mysqli_query($conn, "
    SELECT *
    FROM admin
    ORDER BY id_admin ASC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>

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

            <li>
                <a href="laporan.php">Laporan</a>
            </li>

            <li class="active">
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

            <h1>Pengaturan</h1>

            <div class="profile">
                <a href="LOGIN.php">
                    <?= htmlspecialchars($_SESSION['admin']); ?>
                </a>
            </div>

        </div>

        <!-- Daftar Akun -->
        <div class="table-container">

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:20px;
            ">
                <h2>Daftar Akun</h2>

                <?php if($_SESSION['role'] == 'superadmin'): ?>
                <button
                    class="btn-1"
                    onclick="openTambahAkun()"
                    style="width:auto;padding:12px 20px;">
                    + Tambah Akun
                </button>
                <?php endif; ?>

            </div>

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while($admin = mysqli_fetch_assoc($queryAdmin)):
                    ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td>
                            <?= htmlspecialchars($admin['username']); ?>
                        </td>

                        <td>
                            <?php if($admin['role'] == 'superadmin'): ?>
                                <span class="badge maintenance">Super Admin</span>
                            <?php else: ?>
                                <span class="badge success">Admin</span>
                            <?php endif; ?>
                        </td>

                        <td>

                            <?php if($_SESSION['role'] == 'superadmin'): ?>
                            <button
                                class="btn-1"
                                style="
                                    width:auto;
                                    padding:8px 15px;
                                    margin-right:5px;
                                "onclick="openPasswordModal(
                                    <?= $admin['id_admin']; ?>,
                                    '<?= htmlspecialchars($admin['username']); ?>'
                                )">
                                Ganti Password
                            </button>
                            <?php endif; ?>

                            <?php if($_SESSION['role'] == 'superadmin'): ?>

                                <?php if($admin['username'] != $_SESSION['admin']): ?>

                                    <button
                                        class="btn-1 btnHapus"
                                        style="
                                            width:auto;
                                            padding:8px 15px;
                                            background:#ef4444;
                                        "
                                        data-id="<?= $admin['id_admin']; ?>"
                                        data-username="<?= htmlspecialchars($admin['username']); ?>">
                                        Hapus
                                    </button>

                                <?php else: ?>

                                    <button
                                        style="
                                            width:auto;
                                            padding:8px 15px;
                                            background:#9ca3af;
                                            cursor:not-allowed;
                                        "
                                        class="btn-1"
                                        disabled>
                                        Hapus
                                    </button>

                                <?php endif; ?>

                            <?php endif; ?>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

        <!-- Pengaturan Tampilan -->
        <div class="table-container">

            <h2>Pengaturan Tampilan</h2>

            <table>

                <thead>

                    <tr>
                        <th>Fitur</th>
                        <th>Terakhir Backup</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <tr>
                        <td>Backup Database</td>
                        <td>
                            <?php
                            $backupTerakhir = 'Belum pernah';

                            if (file_exists('backup_info.txt')) {
                                $backupTerakhir = file_get_contents('backup_info.txt');
                            }
                            ?>

                            <span class="status-badge active">
                                <?= $backupTerakhir ?>
                            </span>
                        </td>
                        <td>

                            <a href="backup_database.php">
                                <button
                                    style="
                                        width:auto;
                                        padding:8px 15px;
                                        font-size:14px;
                                        background:#10b981;
                                    "
                                    class="btn-1">
                                    Backup Sekarang
                                </button>
                            </a>

                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div id="modalTambahAkun" class="modal">

    <div class="modal-content">

        <span class="close"
              onclick="closeTambahAkun()">
            &times;
        </span>

        <h2>Tambah Akun Baru</h2>

        <form
            action="proses/tambah_admin.php"
            method="POST">

            <div class="input-group">
                <label>Username</label>
                <input
                    type="text"
                    name="username"
                    required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    required>
            </div>

            <div class="input-group">
                <label>Role</label>

                <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="superadmin">Super Admin</option>
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

<div id="modalPassword" class="modal">

    <div class="modal-content">

        <span
            class="close"
            onclick="closePasswordModal()">
            &times;
        </span>

        <h2>Ganti Password</h2>

        <form
            action="proses/ganti_password.php"
            method="POST">

            <input
                type="hidden"
                id="id_admin"
                name="id_admin">

            <div class="input-group">
                <label>Username</label>
                <input
                    type="text"
                    id="username_admin"
                    readonly>
            </div>

            <div class="input-group">
                <label>Password Baru</label>
                <input
                    type="password"
                    name="password_baru"
                    required>
            </div>

            <div class="input-group">
                <label>Konfirmasi Password</label>
                <input
                    type="password"
                    name="konfirmasi_password"
                    required>
            </div>

            <button
                type="submit"
                class="btn-1">
                Simpan
            </button>

        </form>

    </div>

</div>

<div id="modalHapus" class="modal">

    <div class="modal-content">

        <span class="close" data-modal="hapus">&times;</span>

        <h2>Hapus Akun</h2>

        <p>
            Apakah yakin ingin menghapus akun
            <strong id="namaHapus"></strong>?
        </p>

        <form
            action="proses/hapus_admin.php"
            method="POST">

            <input
                type="hidden"
                id="hapusId"
                name="id_admin">

            <button
                type="submit"
                class="btn-1"
                style="background:#ef4444;">

                Ya, Hapus

            </button>

        </form>

    </div>

</div>

<script>

function openTambahAkun() {
    document.getElementById(
        'modalTambahAkun'
    ).style.display = 'block';
}

function closeTambahAkun() {
    document.getElementById(
        'modalTambahAkun'
    ).style.display = 'none';
}

window.onclick = function(event){
    const modal =
        document.getElementById(
            'modalTambahAkun'
        );

    if(event.target == modal){
        modal.style.display = 'none';
    }

    const modalPassword =
        document.getElementById(
            'modalPassword'
        );

    if(event.target == modalPassword){
        modalPassword.style.display = 'none';
    }

}

function openPasswordModal(
    id,
    username
){

    document.getElementById(
        'id_admin'
    ).value = id;

    document.getElementById(
        'username_admin'
    ).value = username;

    document.getElementById(
        'modalPassword'
    ).style.display = 'block';
}

function closePasswordModal(){

    document.getElementById(
        'modalPassword'
    ).style.display = 'none';
}

const modalHapus =
document.getElementById('modalHapus');

document.querySelectorAll('.btnHapus')
.forEach(btn=>{

    btn.onclick=function(){

        modalHapus.style.display='block';

        document.getElementById('hapusId').value =
        this.dataset.id;

        document.getElementById('namaHapus').textContent =
        this.dataset.username;

    }

});

document.querySelectorAll('.close')
.forEach(btn=>{

    btn.onclick=function(){

        if(this.dataset.modal=="hapus")
        {
            modalHapus.style.display="none";
        }

    }

});

window.onclick=function(e){

    if(e.target==modalHapus)
    {
        modalHapus.style.display="none";
    }

}

</script>

<?php if(isset($_GET['success'])) : ?>
<script>
alert(
    'Password berhasil diubah.'
);
</script>
<?php endif; ?>

<?php if(isset($_GET['error'])) : ?>
<script>
alert(
    'Konfirmasi password tidak sama.'
);
</script>
<?php endif; ?>

</body>
</html>