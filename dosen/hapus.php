<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_GET['id'];

mysqli_query($conn,"
DELETE FROM dosen
WHERE id_dosen='$id'
");

header("Location:index.php");
exit;