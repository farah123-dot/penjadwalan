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
$id_dosen_mk = $_POST['id_dosen_mk'] ?? '';


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
    $id_dosen_mk == ''
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
3. AMBIL DATA DOSEN DAN KELAS
==================================================
*/

$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        dm.id_dosen,
        dm.id_kelas,
        d.nama_dosen,
        k.nama_kelas,
        mk.nama_mk
    FROM dosen_mk dm

    JOIN dosen d
        ON dm.id_dosen = d.id_dosen

    JOIN kelas k
        ON dm.id_kelas = k.id_kelas

    JOIN mata_kuliah mk
        ON dm.id_mk = mk.id_mk

    WHERE dm.id = '$id_dosen_mk'
    LIMIT 1
"));


if (!$data) {

    echo "
    <script>

        alert('Data dosen dan mata kuliah tidak ditemukan.');

        history.back();

    </script>
    ";

    exit;
}


$id_dosen   = $data['id_dosen'];
$id_kelas   = $data['id_kelas'];
$nama_dosen = $data['nama_dosen'];
$nama_kelas = $data['nama_kelas'];
$nama_mk    = $data['nama_mk'];


/*
==================================================
4. AMBIL NAMA HARI DAN JAM
==================================================
*/

$hari_data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT nama_hari
    FROM hari
    WHERE id_hari = '$id_hari'
    LIMIT 1
"));

$nama_hari = $hari_data['nama_hari'] ?? 'Hari';


$jam_data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        jam_mulai,
        jam_selesai
    FROM jam_kuliah
    WHERE id_jam = '$id_jam'
    LIMIT 1
"));

$jam_mulai = isset($jam_data['jam_mulai'])
    ? substr($jam_data['jam_mulai'], 0, 5)
    : '';

$jam_selesai = isset($jam_data['jam_selesai'])
    ? substr($jam_data['jam_selesai'], 0, 5)
    : '';


/*
==================================================
5. CEK RUANGAN
==================================================
*/

$cek_ruangan = mysqli_query($conn, "
    SELECT j.id_jadwal
    FROM jadwal j

    WHERE
        j.id_hari = '$id_hari'
        AND j.id_jam = '$id_jam'
        AND j.id_ruangan = '$id_ruangan'
        AND j.id_jadwal <> '$id_jadwal'

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
6. CEK DOSEN
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
7. CEK KELAS
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
8. UPDATE JADWAL
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
9. HASIL UPDATE
==================================================
*/

if ($update) {

    echo "
    <script>

        alert(
            'Jadwal berhasil diperbarui.'
        );

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert(
            'Gagal memperbarui jadwal.'
        );

        history.back();

    </script>
    ";
}

exit;

?>
