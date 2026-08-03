<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_POST['id_jam'];

$mulai = $_POST['jam_mulai'];
$selesai = $_POST['jam_selesai'];

mysqli_query($conn,"
UPDATE jam_kuliah
SET
jam_mulai='$mulai',
jam_selesai='$selesai'
WHERE id_jam='$id'
");

header("Location:index.php");
exit;