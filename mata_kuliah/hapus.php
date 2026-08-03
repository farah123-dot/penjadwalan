<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_GET['id'];

mysqli_query($conn,"
DELETE FROM mata_kuliah
WHERE id_mk='$id'
");

header("Location:index.php");
exit;