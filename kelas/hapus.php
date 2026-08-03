<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_GET['id'];

mysqli_query($conn,"
DELETE FROM kelas
WHERE id_kelas='$id'
");

header("Location:index.php");
exit;