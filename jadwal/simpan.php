<?php

require_once __DIR__ . "/../koneksi.php";

/*
==================================================
AMBIL DATA DARI FORM
==================================================
*/

$id_dosen       = $_POST['id_dosen'];
$id_kelas_dibuka = $_POST['id_kelas_dibuka'];
$id_hari        = $_POST['id_hari'];
$id_jam         = $_POST['id_jam'];


/*
==================================================
VALIDASI DATA
==================================================
*/

if (
    empty($id_dosen) ||
    empty($id_kelas_dibuka) ||
    empty($id_hari) ||
    empty($id_jam)
) {

    echo "
    <script>
        alert('Data preferensi belum lengkap!');
        window.location='tambah.php';
    </script>
    ";

    exit;
}


/*
==================================================
AMBIL DATA KELAS DAN SEMESTER
==================================================
*/

$data_kelas = mysqli_fetch_assoc(mysqli_query($conn, "

    SELECT
        kd.id_kelas_dibuka,
        kd.id_kelas,
        kd.id_mk,
        mk.semester

    FROM kelas_dibuka kd

    JOIN mata_kuliah mk
        ON kd.id_mk = mk.id_mk

    WHERE kd.id_kelas_dibuka = '$id_kelas_dibuka'

"));


if (!$data_kelas) {

    echo "
    <script>
        alert('Kelas tidak ditemukan!');
        window.location='tambah.php';
    </script>
    ";

    exit;
}

$semester = $data_kelas['semester'];
$id_kelas = $data_kelas['id_kelas'];


/*
==================================================
CEK BENTROK DOSEN
==================================================
*/

$cek_dosen = mysqli_query($conn, "

    SELECT pd.id_preferensi

    FROM preferensi_dosen pd

    WHERE pd.id_dosen = '$id_dosen'

    AND pd.id_hari = '$id_hari'

    AND pd.id_jam = '$id_jam'

");

if (mysqli_num_rows($cek_dosen) > 0) {

    echo "
    <script>
        alert('Dosen sudah memiliki preferensi pada hari dan sesi tersebut!');
        window.location='tambah.php';
    </script>
    ";

    exit;
}


/*
==================================================
CEK BENTROK KELAS
==================================================
*/

$cek_kelas = mysqli_query($conn, "

    SELECT pd.id_preferensi

    FROM preferensi_dosen pd

    JOIN kelas_dibuka kd
        ON pd.id_kelas_dibuka = kd.id_kelas_dibuka

    WHERE kd.id_kelas = '$id_kelas'

    AND pd.id_hari = '$id_hari'

    AND pd.id_jam = '$id_jam'

");

if (mysqli_num_rows($cek_kelas) > 0) {

    echo "
    <script>
        alert('Kelas sudah memiliki preferensi pada hari dan sesi tersebut!');
        window.location='tambah.php';
    </script>
    ";

    exit;
}


/*
==================================================
CEK BATAS 3 KELAS PER SEMESTER
==================================================
*/

$pengaturan = mysqli_fetch_assoc(mysqli_query($conn, "

    SELECT max_kelas_per_semester

    FROM pengaturan_kampus

    LIMIT 1

"));

$max_kelas = $pengaturan['max_kelas_per_semester'];


/*
==================================================
HITUNG JUMLAH KELAS PADA SEMESTER,
HARI DAN SESI YANG SAMA
==================================================
*/

$cek_semester = mysqli_query($conn, "

    SELECT COUNT(*) AS jumlah

    FROM preferensi_dosen pd

    JOIN kelas_dibuka kd
        ON pd.id_kelas_dibuka = kd.id_kelas_dibuka

    JOIN mata_kuliah mk
        ON kd.id_mk = mk.id_mk

    WHERE mk.semester = '$semester'

    AND pd.id_hari = '$id_hari'

    AND pd.id_jam = '$id_jam'

");

$data_semester = mysqli_fetch_assoc($cek_semester);

$jumlah_semester = $data_semester['jumlah'];


/*
==================================================
JIKA KUOTA SEMESTER PENUH
==================================================
*/

if ($jumlah_semester >= $max_kelas) {

    echo "
    <script>

        alert(
            'Sesi yang dipilih sudah penuh untuk semester tersebut. Silakan pilih sesi berikutnya.'
        );

        window.location='tambah.php';

    </script>
    ";

    exit;
}


/*
==================================================
CEK RUANGAN
==================================================
*/

$pengaturan_ruangan = mysqli_fetch_assoc(mysqli_query($conn, "

    SELECT total_ruangan

    FROM pengaturan_kampus

    LIMIT 1

"));

$total_ruangan = $pengaturan_ruangan['total_ruangan'];


/*
==================================================
HITUNG JUMLAH KELAS PADA HARI DAN SESI
==================================================
*/

$cek_ruangan = mysqli_query($conn, "

    SELECT COUNT(*) AS jumlah

    FROM preferensi_dosen pd

    WHERE pd.id_hari = '$id_hari'

    AND pd.id_jam = '$id_jam'

");

$data_ruangan = mysqli_fetch_assoc($cek_ruangan);

$jumlah_ruangan = $data_ruangan['jumlah'];


/*
==================================================
CEK KAPASITAS RUANGAN
==================================================
*/

if ($jumlah_ruangan >= $total_ruangan) {

    echo "
    <script>

        alert(
            'Semua ruangan sudah digunakan pada hari dan sesi tersebut. Silakan pilih sesi lain.'
        );

        window.location='tambah.php';

    </script>
    ";

    exit;
}


/*
==================================================
SIMPAN PREFERENSI DOSEN
==================================================
*/

$sql = mysqli_query($conn, "

    INSERT INTO preferensi_dosen
    (
        id_dosen,
        id_kelas_dibuka,
        id_hari,
        id_jam
    )

    VALUES
    (
        '$id_dosen',
        '$id_kelas_dibuka',
        '$id_hari',
        '$id_jam'
    )

");


/*
==================================================
HASIL
==================================================
*/

if ($sql) {

    echo "
    <script>

        alert('Preferensi dosen berhasil disimpan.');

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert('Gagal menyimpan preferensi dosen!');

        window.location='tambah.php';

    </script>
    ";

}

?>
