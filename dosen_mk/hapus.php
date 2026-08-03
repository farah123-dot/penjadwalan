<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_GET['id'];

mysqli_query($conn,"
DELETE FROM dosen_mk
WHERE id='$id'
");

header("Location:index.php");
exit;