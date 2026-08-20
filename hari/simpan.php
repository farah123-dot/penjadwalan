<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";


$nama_hari = trim($_POST['nama_hari'] ?? '');


/* ===============================
   VALIDASI
================================ */

if ($nama_hari == '') {

    echo "
    <script>

        alert('Nama hari wajib diisi.');

        history.back();

    </script>
    ";

    exit;
}


/* ===============================
   CEK DUPLIKAT
================================ */

$cek = mysqli_query($conn, "

    SELECT id_hari

    FROM hari

    WHERE nama_hari = '$nama_hari'

    LIMIT 1

");


if (mysqli_num_rows($cek) > 0) {

    echo "
    <script>

        alert('Hari tersebut sudah ada.');

        history.back();

    </script>
    ";

    exit;
}


/* ===============================
   SIMPAN
================================ */

$simpan = mysqli_query($conn, "

    INSERT INTO hari
    (
        nama_hari
    )

    VALUES
    (
        '$nama_hari'
    )

");


if ($simpan) {

    echo "
    <script>

        alert('Hari berhasil ditambahkan.');

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert('Gagal menambahkan hari.');

        history.back();

    </script>
    ";
}

exit;

?>
