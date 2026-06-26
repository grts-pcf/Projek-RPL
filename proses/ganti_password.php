<?php
require_once "../config/koneksi.php";

$id_admin =
    (int) $_POST['id_admin'];

$passwordBaru =
    $_POST['password_baru'];

$konfirmasi =
    $_POST['konfirmasi_password'];

if(
    $passwordBaru !=
    $konfirmasi
){
    header(
        "Location: ../pengaturan.php?error=password"
    );
    exit;
}

$passwordHash =
    password_hash(
        $passwordBaru,
        PASSWORD_DEFAULT
    );

mysqli_query(
    $conn,
    "UPDATE admin
     SET password='$passwordHash'
     WHERE id_admin=$id_admin"
);

header(
    "Location: ../pengaturan.php?success=password"
);
exit;
?>