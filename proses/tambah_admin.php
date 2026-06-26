<?php
require_once "../config/koneksi.php";

$username = mysqli_real_escape_string(
    $conn,
    $_POST['username']
);

$password = password_hash(
    $_POST['password'],
    PASSWORD_DEFAULT
);

mysqli_query($conn,"
    INSERT INTO admin
    (
        username,
        password
    )
    VALUES
    (
        '$username',
        '$password'
    )
");

header("Location: ../pengaturan.php");
exit;
?>