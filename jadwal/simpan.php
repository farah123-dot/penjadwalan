<?php

require_once __DIR__ . "/../koneksi.php";

/*
==================================================
AMBIL DATA DARI FORM
==================================================
*/

$id_dosen        = $_POST['id_dosen'] ?? '';
$id_kelas_dibuka = $_POST['id_kelas_dibuka'] ?? '';
$id_hari         = $_POST['id_hari'] ?? '';
$id_jam          = $_POST['id_jam'] ?? '';


/*
==================================================
VALIDASI INPUT
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
        alert('Data belum lengkap!');
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

$query_kelas = mysqli_query($conn, "

    SELECT
        kd.id_kelas,
        kd.id_mk,
        mk.semester,
        mk.nama_mk,
        kd.nama_kelas

    FROM kelas_dibuka kd

    JOIN mata_kuliah mk
        ON kd.id_mk = mk.id_mk

    WHERE kd.id_kelas_dibuka = '$id_kelas_dibuka'

");

$data_kelas = mysqli_fetch_assoc($query_kelas);


if (!$data_kelas) {

    echo "
    <script>
        alert('Kelas tidak ditemukan!');
        window.location='tambah.php';
    </script>
    ";

    exit;
}


$id_kelas = $data_kelas['id_kelas'];
$semester = $data_kelas['semester'];


/*
==================================================
AMBIL PENGATURAN KAMPUS
==================================================
*/

$query_pengaturan = mysqli_query($conn, "

    SELECT
        max_kelas_per_semester,
        total_ruangan

    FROM pengaturan_kampus

    LIMIT 1

");

$pengaturan = mysqli_fetch_assoc($query_pengaturan);

$max_kelas = $pengaturan['max_kelas_per_semester'];
$total_ruangan = $pengaturan['total_ruangan'];


/*
==================================================
FILTER 1
CEK KETERSEDIAAN RUANGAN
==================================================
*/

$query_ruangan = mysqli_query($conn, "

    SELECT r.id_ruangan

    FROM ruangan r

    WHERE r.id_ruangan NOT IN (

        SELECT j.id_ruangan

        FROM jadwal j

        WHERE j.id_hari = '$id_hari'
        AND j.id_jam = '$id_jam'

    )

    ORDER BY r.id_ruangan

    LIMIT 1

");


/*
==================================================
JIKA TIDAK ADA RUANGAN
==================================================
*/

if (mysqli_num_rows($query_ruangan) == 0) {

    /*
    ----------------------------------------------
    CARI SESI BERIKUTNYA
    ----------------------------------------------
    */

    $query_sesi_berikutnya = mysqli_query($conn, "

        SELECT id_jam

        FROM jam_kuliah

        WHERE id_jam > '$id_jam'

        ORDER BY id_jam ASC

        LIMIT 1

    ");

    $sesi_berikutnya = mysqli_fetch_assoc($query_sesi_berikutnya);


    if ($sesi_berikutnya) {

        $id_jam_baru = $sesi_berikutnya['id_jam'];

        echo "
        <script>

            alert(
                'Semua ruangan pada sesi yang dipilih sudah penuh. Jadwal akan dipindahkan ke sesi berikutnya.'
            );

            window.location='tambah.php';

        </script>
        ";

        exit;

    }


    echo "
    <script>

        alert(
            'Tidak ada ruangan tersedia pada hari tersebut.'
        );

        window.location='tambah.php';

    </script>
    ";

    exit;
}


/*
==================================================
AMBIL RUANGAN YANG TERSEDIA
==================================================
*/

$data_ruangan = mysqli_fetch_assoc($query_ruangan);

$id_ruangan = $data_ruangan['id_ruangan'];


/*
==================================================
FILTER 2
BATAS MAKSIMAL KELAS PER SEMESTER
==================================================
*/

$query_semester = mysqli_query($conn, "

    SELECT COUNT(*) AS jumlah

    FROM jadwal j

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    JOIN mata_kuliah mk
        ON dm.id_mk = mk.id_mk

    WHERE mk.semester = '$semester'

    AND j.id_hari = '$id_hari'

    AND j.id_jam = '$id_jam'

");

$data_semester = mysqli_fetch_assoc($query_semester);

$jumlah_semester = $data_semester['jumlah'];


/*
==================================================
JIKA SEMESTER SUDAH PENUH
==================================================
*/

if ($jumlah_semester >= $max_kelas) {

    /*
    ----------------------------------------------
    CARI SESI BERIKUTNYA
    ----------------------------------------------
    */

    $query_sesi_berikutnya = mysqli_query($conn, "

        SELECT id_jam

        FROM jam_kuliah

        WHERE id_jam > '$id_jam'

        ORDER BY id_jam ASC

        LIMIT 1

    ");

    $sesi_berikutnya = mysqli_fetch_assoc($query_sesi_berikutnya);


    if ($sesi_berikutnya) {

        echo "
        <script>

            alert(
                'Kuota semester pada sesi tersebut sudah penuh. Silakan gunakan sesi berikutnya.'
            );

            window.location='tambah.php';

        </script>
        ";

        exit;

    }


    echo "
    <script>

        alert(
            'Kuota semester sudah penuh dan tidak ada sesi berikutnya.'
        );

        window.location='tambah.php';

    </script>
    ";

    exit;
}


/*
==================================================
FILTER 3
CEK BENTROK DOSEN
==================================================
*/

$query_dosen = mysqli_query($conn, "

    SELECT j.id_jadwal

    FROM jadwal j

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    WHERE dm.id_dosen = '$id_dosen'

    AND j.id_hari = '$id_hari'

    AND j.id_jam = '$id_jam'

");


if (mysqli_num_rows($query_dosen) > 0) {

    echo "
    <script>

        alert(
            'Dosen sudah memiliki jadwal pada hari dan sesi tersebut!'
        );

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

$query_kelas_bentrok = mysqli_query($conn, "

    SELECT j.id_jadwal

    FROM jadwal j

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    WHERE dm.id_kelas = '$id_kelas'

    AND j.id_hari = '$id_hari'

    AND j.id_jam = '$id_jam'

");


if (mysqli_num_rows($query_kelas_bentrok) > 0) {

    echo "
    <script>

        alert(
            'Kelas sudah memiliki jadwal pada hari dan sesi tersebut!'
        );

        window.location='tambah.php';

    </script>
    ";

    exit;
}


/*
==================================================
CARI / BUAT RELASI DOSEN - MK - KELAS
==================================================
*/

$query_dm = mysqli_query($conn, "

    SELECT id

    FROM dosen_mk

    WHERE id_dosen = '$id_dosen'

    AND id_mk = '" . $data_kelas['id_mk'] . "'

    AND id_kelas = '$id_kelas'

    LIMIT 1

");

$data_dm = mysqli_fetch_assoc($query_dm);


/*
==================================================
JIKA RELASI BELUM ADA
==================================================
*/

if (!$data_dm) {

    mysqli_query($conn, "

        INSERT INTO dosen_mk
        (
            id_dosen,
            id_mk,
            id_kelas
        )

        VALUES
        (
            '$id_dosen',
            '" . $data_kelas['id_mk'] . "',
            '$id_kelas'
        )

    ");

    $id_dosen_mk = mysqli_insert_id($conn);

} else {

    $id_dosen_mk = $data_dm['id'];

}


/*
==================================================
SIMPAN JADWAL
==================================================
*/

$sql = mysqli_query($conn, "

    INSERT INTO jadwal
    (
        id_hari,
        id_jam,
        id_ruangan,
        id_dosen_mk
    )

    VALUES
    (
        '$id_hari',
        '$id_jam',
        '$id_ruangan',
        '$id_dosen_mk'
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

        alert(
            'Jadwal berhasil disimpan.'
        );

        window.location='index.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert(
            'Gagal menyimpan jadwal!'
        );

        window.location='tambah.php';

    </script>
    ";

}

?>
