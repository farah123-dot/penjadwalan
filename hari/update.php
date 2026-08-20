<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";


$id_hari   = $_POST['id_hari'] ?? '';
$nama_hari = trim($_POST['nama_hari'] ?? '');


/* ===============================
   VALIDASI
================================ */

if ($id_hari == '' || $nama_hari == '') {

    echo "
    <script>

        alert('Data belum lengkap.');

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

    AND id_hari <> '$id_hari'

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
   UPDATE
================================ */

$update = mysqli_query($conn, "

    UPDATE hari

    SET
        nama_hari = '$nama_hari'

    WHERE id_hari = '$id_hari'

");


if ($update) {

    echo "
    <script>

        alert('Hari berhasil diperbarui.');

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert('Gagal memperbarui hari.');

        history.back();

    </script>
    ";
}

exit;

?>
