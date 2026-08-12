<?php

require_once __DIR__ . "/../koneksi.php";

$id_dosen        = $_POST['id_dosen'] ?? '';
$id_kelas_dibuka = $_POST['id_kelas_dibuka'] ?? '';
$id_hari         = $_POST['id_hari'] ?? '';
$id_jam_awal     = $_POST['id_jam'] ?? '';

/*
==================================================
VALIDASI
==================================================
*/

if (
    empty($id_dosen) ||
    empty($id_kelas_dibuka) ||
    empty($id_hari) ||
    empty($id_jam_awal)
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
AMBIL DATA KELAS
==================================================
*/

$query_kelas = mysqli_query($conn, "

    SELECT
        kd.id_kelas_dibuka,
        kd.id_kelas,
        kd.id_mk,
        kd.nama_kelas,
        mk.nama_mk,
        mk.semester

    FROM kelas_dibuka kd

    JOIN mata_kuliah mk
        ON kd.id_mk = mk.id_mk

    WHERE kd.id_kelas_dibuka = '$id_kelas_dibuka'

    LIMIT 1

");

$data_kelas = mysqli_fetch_assoc($query_kelas);

if (!$data_kelas) {

    echo "
    <script>
        alert('Kelas yang dipilih tidak ditemukan!');
        window.location='tambah.php';
    </script>
    ";

    exit;
}

$id_kelas = $data_kelas['id_kelas'];
$id_mk    = $data_kelas['id_mk'];
$semester = $data_kelas['semester'];


/*
==================================================
AMBIL PENGATURAN
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

$max_kelas    = (int) $pengaturan['max_kelas_per_semester'];
$total_ruangan = (int) $pengaturan['total_ruangan'];


/*
==================================================
CARI SESI MULAI DARI PILIHAN DOSEN
==================================================
*/

$query_jam = mysqli_query($conn, "

    SELECT
        id_jam,
        jam_mulai,
        jam_selesai

    FROM jam_kuliah

    WHERE id_jam >= '$id_jam_awal'

    ORDER BY id_jam ASC

");


$jadwal_tersedia = false;


/*
==================================================
CEK SATU PER SATU SESI
==================================================
*/

while ($jam = mysqli_fetch_assoc($query_jam)) {

    $id_jam = $jam['id_jam'];


    /*
    ==================================================
    FILTER 1
    CEK DOSEN
    ==================================================
    */

    $cek_dosen = mysqli_query($conn, "

        SELECT j.id_jadwal

        FROM jadwal j

        JOIN dosen_mk dm
            ON j.id_dosen_mk = dm.id

        WHERE dm.id_dosen = '$id_dosen'

        AND j.id_hari = '$id_hari'

        AND j.id_jam = '$id_jam'

        LIMIT 1

    ");

    if (mysqli_num_rows($cek_dosen) > 0) {

        continue;

    }


    /*
    ==================================================
    FILTER 2
    CEK KELAS
    ==================================================
    */

    $cek_kelas = mysqli_query($conn, "

        SELECT j.id_jadwal

        FROM jadwal j

        JOIN dosen_mk dm
            ON j.id_dosen_mk = dm.id

        WHERE dm.id_kelas = '$id_kelas'

        AND j.id_hari = '$id_hari'

        AND j.id_jam = '$id_jam'

        LIMIT 1

    ");

    if (mysqli_num_rows($cek_kelas) > 0) {

        continue;

    }


    /*
    ==================================================
    FILTER 3
    MAKSIMAL 3 KELAS PER SEMESTER
    ==================================================
    */

    $cek_semester = mysqli_query($conn, "

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

    $data_semester = mysqli_fetch_assoc($cek_semester);

    $jumlah_semester = (int) $data_semester['jumlah'];


    if ($jumlah_semester >= $max_kelas) {

        continue;

    }


    /*
    ==================================================
    FILTER 4
    CARI RUANGAN YANG KOSONG
    ==================================================
    */

    $query_ruangan = mysqli_query($conn, "

        SELECT r.id_ruangan

        FROM ruangan r

        WHERE NOT EXISTS (

            SELECT 1

            FROM jadwal j

            WHERE j.id_ruangan = r.id_ruangan

            AND j.id_hari = '$id_hari'

            AND j.id_jam = '$id_jam'

        )

        ORDER BY r.id_ruangan ASC

        LIMIT 1

    ");

    if (mysqli_num_rows($query_ruangan) == 0) {

        continue;

    }

    $data_ruangan = mysqli_fetch_assoc($query_ruangan);

    $id_ruangan = $data_ruangan['id_ruangan'];


    /*
    ==================================================
    CARI RELASI DOSEN - MK - KELAS
    ==================================================
    */

    $query_dm = mysqli_query($conn, "

        SELECT id

        FROM dosen_mk

        WHERE id_dosen = '$id_dosen'

        AND id_mk = '$id_mk'

        AND id_kelas = '$id_kelas'

        LIMIT 1

    ");

    $data_dm = mysqli_fetch_assoc($query_dm);


    /*
    ==================================================
    BUAT RELASI JIKA BELUM ADA
    ==================================================
    */

    if (!$data_dm) {

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
                alert('Gagal membuat data dosen mengajar!');
                window.location='tambah.php';
            </script>
            ";

            exit;
        }

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


    if ($sql) {

        $jadwal_tersedia = true;

        $jam_mulai = substr($jam['jam_mulai'], 0, 5);
        $jam_selesai = substr($jam['jam_selesai'], 0, 5);


        /*
        ==============================================
        CEK APAKAH SESI BERUBAH
        ==============================================
        */

        if ($id_jam != $id_jam_awal) {

            echo "
            <script>

                alert(
                    'Sesi yang dipilih penuh atau bentrok. Jadwal otomatis dipindahkan ke sesi $jam_mulai - $jam_selesai.'
                );

                window.location='index.php';

            </script>
            ";

        } else {

            echo "
            <script>

                alert(
                    'Jadwal berhasil disimpan pada sesi $jam_mulai - $jam_selesai.'
                );

                window.location='index.php';

            </script>
            ";

        }

        exit;

    }

}


/*
==================================================
TIDAK ADA SESI YANG TERSEDIA
==================================================
*/

if (!$jadwal_tersedia) {

    echo "
    <script>

        alert(
            'Tidak ada sesi dan ruangan yang tersedia untuk jadwal tersebut.'
        );

        window.location='tambah.php';

    </script>
    ";

    exit;
}

?>
