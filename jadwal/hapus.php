<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_GET['id'];

mysqli_query($conn,"
DELETE FROM jadwal
WHERE id_jadwal='$id'
");

header("Location:index.php");
exit;