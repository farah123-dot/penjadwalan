<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";


/*
==================================================
1. AMBIL ID SESI
==================================================
*/

$id = $_GET['id'] ?? '';


/*
==================================================
2. VALIDASI ID
==================================================
*/

if ($id == '') {

    echo "
    <script>

        alert('ID sesi kuliah tidak ditemukan.');

        window.location='index.php';

    </script>
    ";

    exit;
}


/*
==================================================
3. CEK SESI
==================================================
*/

$cek = mysqli_query($conn, "

    SELECT id_jam

    FROM jam_kuliah

    WHERE id_jam = '$id'

    LIMIT 1

");


if (mysqli_num_rows($cek) == 0) {

    echo "
    <script>

        alert('Sesi kuliah tidak ditemukan.');

        window.location='index.php';

    </script>
    ";

    exit;
}


/*
==================================================
4. CEK APAKAH SESI SUDAH DIPAKAI JADWAL
==================================================
*/

$cek_jadwal = mysqli_query($conn, "

    SELECT id_jadwal

    FROM jadwal

    WHERE id_jam = '$id'

    LIMIT 1

");


if (mysqli_num_rows($cek_jadwal) > 0) {

    echo "
    <script>

        alert(
            'Sesi kuliah tidak dapat dihapus karena masih digunakan oleh jadwal kuliah.'
        );

        window.location='index.php';

    </script>
    ";

    exit;
}


/*
==================================================
5. HAPUS SESI
==================================================
*/

$hapus = mysqli_query($conn, "

    DELETE FROM jam_kuliah

    WHERE id_jam = '$id'

");


/*
==================================================
6. HASIL
==================================================
*/

if ($hapus) {

    echo "
    <script>

        alert('Sesi kuliah berhasil dihapus.');

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert('Gagal menghapus sesi kuliah.');

        window.location='index.php';

    </script>
    ";

}

exit;

?>
