<?php

require_once __DIR__ . "/../koneksi.php";

$mulai = $_POST['jam_mulai'];
$selesai = $_POST['jam_selesai'];

mysqli_query($conn,"
INSERT INTO jam_kuliah(jam_mulai,jam_selesai)
VALUES('$mulai','$selesai')
");

header("Location:index.php");
exit;