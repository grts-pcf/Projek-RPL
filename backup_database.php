<?php
require 'config/koneksi.php';

$tables = array();
$result = mysqli_query($conn, "SHOW TABLES");

while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}

$return = '';

foreach ($tables as $table) {

    $result = mysqli_query($conn, "SELECT * FROM $table");
    $num_fields = mysqli_num_fields($result);

    $row2 = mysqli_fetch_row(
        mysqli_query($conn, "SHOW CREATE TABLE $table")
    );

    $return .= "\n\n".$row2[1].";\n\n";

    while ($row = mysqli_fetch_row($result)) {
        $return .= "INSERT INTO $table VALUES(";

        for ($j = 0; $j < $num_fields; $j++) {

            if (isset($row[$j])) {
                $return .= '"' .
                    addslashes($row[$j]) .
                    '"';
            } else {
                $return .= '""';
            }

            if ($j < ($num_fields - 1)) {
                $return .= ',';
            }
        }

        $return .= ");\n";
    }

    $return .= "\n\n";
}

$fileName =
    "backup_db_peminjaman_kendaraan_" .
    date("Y-m-d_H-i-s") .
    ".sql";

file_put_contents(
    'backup_info.txt',
    date('d-m-Y H:i:s')
);

header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=$fileName");

echo $return;
exit;
?>