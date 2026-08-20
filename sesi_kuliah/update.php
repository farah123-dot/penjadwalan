<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";


/*
==================================================
1. AMBIL DATA FORM
==================================================
*/

$id_jam      = $_POST['id_jam'] ?? '';
$jam_mulai   = $_POST['jam_mulai'] ?? '';
$jam_selesai = $_POST['jam_selesai'] ?? '';


/*
==================================================
2. VALIDASI
==================================================
*/

if (
    $id_jam == '' ||
    $jam_mulai == '' ||
    $jam_selesai == ''
) {

    echo "
    <script>

        alert('Data sesi kuliah belum lengkap.');

        history.back();

    </script>
    ";

    exit;
}


/*
==================================================
3. VALIDASI WAKTU
==================================================
*/

if ($jam_mulai >= $jam_selesai) {

    echo "
    <script>

        alert('Jam selesai harus lebih besar dari jam mulai.');

        history.back();

    </script>
    ";

    exit;
}


/*
==================================================
4. CEK SESI DUPLIKAT
==================================================
*/

$cek = mysqli_query($conn, "

    SELECT id_jam

    FROM jam_kuliah

    WHERE
        jam_mulai = '$jam_mulai'
        AND jam_selesai = '$jam_selesai'
        AND id_jam <> '$id_jam'

    LIMIT 1

");


if (mysqli_num_rows($cek) > 0) {

    echo "
    <script>

        alert('Sesi dengan waktu tersebut sudah tersedia.');

        history.back();

    </script>
    ";

    exit;
}


/*
==================================================
5. UPDATE DATA
==================================================
*/

$update = mysqli_query($conn, "

    UPDATE jam_kuliah

    SET
        jam_mulai = '$jam_mulai',
        jam_selesai = '$jam_selesai'

    WHERE id_jam = '$id_jam'

");


/*
==================================================
6. HASIL UPDATE
==================================================
*/

if ($update) {

    echo "
    <script>

        alert('Sesi kuliah berhasil diperbarui.');

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert('Gagal memperbarui sesi kuliah.');

        history.back();

    </script>
    ";

}

exit;

?>
