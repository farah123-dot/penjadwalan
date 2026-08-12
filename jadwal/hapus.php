<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";


/*
==================================================
1. AMBIL ID JADWAL
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

        alert('ID jadwal tidak ditemukan.');

        window.location='index.php';

    </script>
    ";

    exit;
}


/*
==================================================
3. CEK DATA JADWAL
==================================================
*/

$cek = mysqli_query($conn, "
    SELECT id_jadwal
    FROM jadwal
    WHERE id_jadwal = '$id'
    LIMIT 1
");


if (mysqli_num_rows($cek) == 0) {

    echo "
    <script>

        alert('Jadwal tidak ditemukan.');

        window.location='index.php';

    </script>
    ";

    exit;
}


/*
==================================================
4. HAPUS JADWAL
==================================================
*/

$hapus = mysqli_query($conn, "
    DELETE FROM jadwal
    WHERE id_jadwal = '$id'
");


/*
==================================================
5. HASIL HAPUS
==================================================
*/

if ($hapus) {

    echo "
    <script>

        alert('Jadwal berhasil dihapus.');

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert('Gagal menghapus jadwal.');

        window.location='index.php';

    </script>
    ";
}

exit;

?>
