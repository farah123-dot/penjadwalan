<?php

require_once __DIR__ . "/../koneksi.php";

$id_dosen = $_POST['id_dosen'];
$id_mk     = $_POST['id_mk'];
$id_kelas  = $_POST['id_kelas'];

mysqli_query($conn,"
INSERT INTO dosen_mk
(
    id_dosen,
    id_mk,
    id_kelas
)
VALUES
(
    '$id_dosen',
    '$id_mk',
    '$id_kelas'
)
");

header("Location:index.php");
exit;