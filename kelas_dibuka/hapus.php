<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";


$id = $_GET['id'] ?? '';


if ($id == '') {

    header("Location:index.php");
    exit;

}


/* ===============================
   CEK APAKAH SUDAH DIPAKAI JADWAL
================================ */

$cek = mysqli_query($conn, "

    SELECT id_jadwal

    FROM jadwal j

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    WHERE dm.id_kelas = (

        SELECT id_kelas
        FROM kelas_dibuka
        WHERE id_kelas_dibuka = '$id'

    )

    AND dm.id_mk = (

        SELECT id_mk
        FROM kelas_dibuka
        WHERE id_kelas_dibuka = '$id'

    )

    LIMIT 1

");


if (mysqli_num_rows($cek) > 0) {

    echo "
    <script>

        alert(
            'Kelas dibuka tidak dapat dihapus karena sudah digunakan dalam jadwal.'
        );

        window.location='index.php';

    </script>
    ";

    exit;
}


/* ===============================
   HAPUS
================================ */

$hapus = mysqli_query($conn, "

    DELETE FROM kelas_dibuka

    WHERE id_kelas_dibuka = '$id'

");


if ($hapus) {

    echo "
    <script>

        alert('Data kelas dibuka berhasil dihapus.');

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert('Gagal menghapus data.');

        window.location='index.php';

    </script>
    ";
}

exit;
?>
