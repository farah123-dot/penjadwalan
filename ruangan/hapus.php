<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_GET['id'];

mysqli_query($conn,"
DELETE FROM ruangan
WHERE id_ruangan='$id'
");

header("Location:index.php");
exit;