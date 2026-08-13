<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";


/*
==================================================
1. AMBIL DATA FORM
==================================================
*/

$id_jadwal   = $_POST['id_jadwal'] ?? '';
$id_hari     = $_POST['id_hari'] ?? '';
$id_jam      = $_POST['id_jam'] ?? '';
$id_ruangan  = $_POST['id_ruangan'] ?? '';
$id_dosen    = $_POST['id_dosen'] ?? '';
$id_kelas_mk = $_POST['id_kelas_mk'] ?? '';


/*
==================================================
2. VALIDASI
==================================================
*/

if (
    $id_jadwal == '' ||
    $id_hari == '' ||
    $id_jam == '' ||
    $id_ruangan == '' ||
    $id_dosen == '' ||
    $id_kelas_mk == ''
) {

    echo "
    <script>

        alert('Data edit belum lengkap.');

        history.back();

    </script>
    ";

    exit;
}


/*
==================================================
3. AMBIL DATA KELAS + MATA KULIAH
==================================================
*/

$data_km = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        dm.id_kelas,
        dm.id_mk,
        k.nama_kelas,
        mk.nama_mk
    FROM dosen_mk dm

    JOIN kelas k
        ON dm.id_kelas = k.id_kelas

    JOIN mata_kuliah mk
        ON dm.id_mk = mk.id_mk

    WHERE dm.id = '$id_kelas_mk'

    LIMIT 1
"));


if (!$data_km) {

    echo "
    <script>

        alert('Data kelas dan mata kuliah tidak ditemukan.');

        history.back();

    </script>
    ";

    exit;
}


$id_kelas   = $data_km['id_kelas'];
$id_mk      = $data_km['id_mk'];
$nama_kelas = $data_km['nama_kelas'];
$nama_mk    = $data_km['nama_mk'];


/*
==================================================
4. AMBIL NAMA DOSEN
==================================================
*/

$data_dosen = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT nama_dosen
    FROM dosen
    WHERE id_dosen = '$id_dosen'
    LIMIT 1
"));


$nama_dosen = $data_dosen['nama_dosen'] ?? 'Dosen';


/*
==================================================
5. AMBIL NAMA HARI
==================================================
*/

$data_hari = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT nama_hari
    FROM hari
    WHERE id_hari = '$id_hari'
    LIMIT 1
"));


$nama_hari = $data_hari['nama_hari'] ?? 'Hari';


/*
==================================================
6. AMBIL JAM
==================================================
*/

$data_jam = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        jam_mulai,
        jam_selesai
    FROM jam_kuliah
    WHERE id_jam = '$id_jam'
    LIMIT 1
"));


$jam_mulai = substr($data_jam['jam_mulai'], 0, 5);
$jam_selesai = substr($data_jam['jam_selesai'], 0, 5);


/*
==================================================
7. CEK RUANGAN
==================================================
*/

$cek_ruangan = mysqli_query($conn, "
    SELECT id_jadwal
    FROM jadwal

    WHERE
        id_hari = '$id_hari'
        AND id_jam = '$id_jam'
        AND id_ruangan = '$id_ruangan'
        AND id_jadwal <> '$id_jadwal'

    LIMIT 1
");


if (mysqli_num_rows($cek_ruangan) > 0) {

    echo "
    <script>

        alert(
            'Ruangan sudah digunakan pada $nama_hari pukul $jam_mulai - $jam_selesai.'
        );

        history.back();

    </script>
    ";

    exit;
}


/*
==================================================
8. CEK DOSEN
==================================================
*/

$cek_dosen = mysqli_query($conn, "
    SELECT j.id_jadwal

    FROM jadwal j

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    WHERE
        j.id_hari = '$id_hari'
        AND j.id_jam = '$id_jam'
        AND dm.id_dosen = '$id_dosen'
        AND j.id_jadwal <> '$id_jadwal'

    LIMIT 1
");


if (mysqli_num_rows($cek_dosen) > 0) {

    echo "
    <script>

        alert(
            'Dosen $nama_dosen sudah memiliki jadwal pada $nama_hari pukul $jam_mulai - $jam_selesai.'
        );

        history.back();

    </script>
    ";

    exit;
}


/*
==================================================
9. CEK KELAS
==================================================
*/

$cek_kelas = mysqli_query($conn, "
    SELECT j.id_jadwal

    FROM jadwal j

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    WHERE
        j.id_hari = '$id_hari'
        AND j.id_jam = '$id_jam'
        AND dm.id_kelas = '$id_kelas'
        AND j.id_jadwal <> '$id_jadwal'

    LIMIT 1
");


if (mysqli_num_rows($cek_kelas) > 0) {

    echo "
    <script>

        alert(
            'Kelas $nama_kelas sudah memiliki jadwal pada $nama_hari pukul $jam_mulai - $jam_selesai.'
        );

        history.back();

    </script>
    ";

    exit;
}


/*
==================================================
10. CARI / BUAT RELASI DOSEN - MK - KELAS
==================================================
*/

$query_dm = mysqli_query($conn, "
    SELECT id
    FROM dosen_mk

    WHERE
        id_dosen = '$id_dosen'
        AND id_mk = '$id_mk'
        AND id_kelas = '$id_kelas'

    LIMIT 1
");


if (mysqli_num_rows($query_dm) > 0) {

    $data_dm = mysqli_fetch_assoc($query_dm);

    $id_dosen_mk = $data_dm['id'];

} else {

    $buat_dm = mysqli_query($conn, "
        INSERT INTO dosen_mk
        (
            id_dosen,
            id_mk,
            id_kelas
        )

        VALUES
        (
            '$id_dosen',
            '$id_mk',
            '$id_kelas'
        )
    ");


    if (!$buat_dm) {

        echo "
        <script>

            alert('Gagal menyimpan relasi dosen, mata kuliah, dan kelas.');

            history.back();

        </script>
        ";

        exit;
    }


    $id_dosen_mk = mysqli_insert_id($conn);
}


/*
==================================================
11. UPDATE JADWAL
==================================================
*/

$update = mysqli_query($conn, "
    UPDATE jadwal

    SET
        id_hari = '$id_hari',
        id_jam = '$id_jam',
        id_ruangan = '$id_ruangan',
        id_dosen_mk = '$id_dosen_mk'

    WHERE id_jadwal = '$id_jadwal'
");


/*
==================================================
12. HASIL
==================================================
*/

if ($update) {

    echo "
    <script>

        alert('Jadwal berhasil diperbarui.');

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert('Gagal memperbarui jadwal.');

        history.back();

    </script>
    ";
}

exit;

?>
