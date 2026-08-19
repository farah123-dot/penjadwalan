<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";


$id_kelas = $_POST['id_kelas'] ?? '';
$id_mk    = $_POST['id_mk'] ?? '';


/* ===============================
   VALIDASI
================================ */

if ($id_kelas == '' || $id_mk == '') {

    echo "
    <script>

        alert('Kelas dan mata kuliah wajib dipilih.');

        history.back();

    </script>
    ";

    exit;
}


/* ===============================
   CEK DUPLIKAT
================================ */

$cek = mysqli_query($conn, "

    SELECT id_kelas_dibuka

    FROM kelas_dibuka

    WHERE id_kelas = '$id_kelas'
    AND id_mk = '$id_mk'

    LIMIT 1

");


if (mysqli_num_rows($cek) > 0) {

    echo "
    <script>

        alert('Kelas dan mata kuliah tersebut sudah dibuka.');

        history.back();

    </script>
    ";

    exit;
}


/* ===============================
   SIMPAN
================================ */

$simpan = mysqli_query($conn, "

    INSERT INTO kelas_dibuka
    (
        id_kelas,
        id_mk
    )

    VALUES
    (
        '$id_kelas',
        '$id_mk'
    )

");


if ($simpan) {

    echo "
    <script>

        alert('Kelas dibuka berhasil ditambahkan.');

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert('Gagal menambahkan kelas dibuka.');

        history.back();

    </script>
    ";
}

exit;
?>
