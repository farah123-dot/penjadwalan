<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";


$id_kelas_dibuka = $_POST['id_kelas_dibuka'] ?? '';
$id_kelas        = $_POST['id_kelas'] ?? '';
$id_mk           = $_POST['id_mk'] ?? '';


/* ===============================
   VALIDASI
================================ */

if (
    $id_kelas_dibuka == '' ||
    $id_kelas == '' ||
    $id_mk == ''
) {

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

    SELECT id_kelas_dibuka

    FROM kelas_dibuka

    WHERE id_kelas = '$id_kelas'
    AND id_mk = '$id_mk'
    AND id_kelas_dibuka <> '$id_kelas_dibuka'

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
   UPDATE
================================ */

$update = mysqli_query($conn, "

    UPDATE kelas_dibuka

    SET
        id_kelas = '$id_kelas',
        id_mk = '$id_mk'

    WHERE id_kelas_dibuka = '$id_kelas_dibuka'

");


if ($update) {

    echo "
    <script>

        alert('Data kelas dibuka berhasil diperbarui.');

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert('Gagal memperbarui data.');

        history.back();

    </script>
    ";
}

exit;
?>
