<?php
session_start();
require_once "../config/koneksi.php";

if(!isset($_SESSION['admin'])){
    header("Location: ../LOGIN.php");
    exit;
}

if($_SESSION['role'] != 'superadmin'){
    die("Akses ditolak.");
}

$username = mysqli_real_escape_string($conn, $_POST['username']);

$password = password_hash(
    $_POST['password'],
    PASSWORD_DEFAULT
);

$role = $_POST['role'];

if(!in_array($role, ['admin', 'superadmin'])){
    die("Role tidak valid.");
}

$cek = mysqli_query($conn,"
    SELECT id_admin
    FROM admin
    WHERE username='$username'
");

if(mysqli_num_rows($cek) > 0){
    die("Username sudah digunakan.");
}

mysqli_query($conn,"
    INSERT INTO admin
    (
        username,
        password,
        role
    )
    VALUES
    (
        '$username',
        '$password',
        '$role'
    )
");

header("Location: ../pengaturan.php");
exit;
?>