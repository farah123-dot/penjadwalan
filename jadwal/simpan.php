<?php

require_once __DIR__ . "/../koneksi.php";

/*
==================================================
1. AMBIL DATA FORM
==================================================
*/

$id_dosen        = $_POST['id_dosen'] ?? '';
$id_kelas_dibuka = $_POST['id_kelas_dibuka'] ?? '';
$id_hari         = $_POST['id_hari'] ?? '';
$id_jam_awal     = $_POST['id_jam'] ?? '';


/*
==================================================
2. VALIDASI INPUT
==================================================
*/

if (
    $id_dosen == '' ||
    $id_kelas_dibuka == '' ||
    $id_hari == '' ||
    $id_jam_awal == ''
) {

    echo "
    <script>
        alert('Data belum lengkap. Silakan isi semua pilihan.');
        window.location='tambah.php';
    </script>
    ";

    exit;
}


/*
==================================================
3. AMBIL NAMA DOSEN DAN HARI
==================================================
*/

$query_dosen_info = mysqli_query($conn, "
    SELECT nama_dosen
    FROM dosen
    WHERE id_dosen = '$id_dosen'
    LIMIT 1
");

$data_dosen_info = mysqli_fetch_assoc($query_dosen_info);

$nama_dosen = $data_dosen_info['nama_dosen'] ?? 'Dosen';


$query_hari_info = mysqli_query($conn, "
    SELECT nama_hari
    FROM hari
    WHERE id_hari = '$id_hari'
    LIMIT 1
");

$data_hari_info = mysqli_fetch_assoc($query_hari_info);

$nama_hari = $data_hari_info['nama_hari'] ?? 'Hari';


/*
==================================================
4. AMBIL DATA KELAS DIBUKA
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


if (!$query_kelas || mysqli_num_rows($query_kelas) == 0) {

    echo "
    <script>
        alert('Kelas yang dipilih tidak ditemukan.');
        window.location='tambah.php';
    </script>
    ";

    exit;
}


$data_kelas = mysqli_fetch_assoc($query_kelas);

$id_kelas = $data_kelas['id_kelas'];
$id_mk    = $data_kelas['id_mk'];
$semester = $data_kelas['semester'];
$nama_kelas = $data_kelas['nama_kelas'];
$nama_mk = $data_kelas['nama_mk'];


/*
==================================================
5. AMBIL PENGATURAN KAMPUS
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

$max_kelas = (int) ($pengaturan['max_kelas_per_semester'] ?? 3);
$total_ruangan = (int) ($pengaturan['total_ruangan'] ?? 0);


/*
==================================================
6. CEK BENTROK DOSEN PADA SESI YANG DIPILIH
==================================================
*/

$cek_dosen_awal = mysqli_query($conn, "
    SELECT
        j.id_jadwal,
        jk.jam_mulai,
        jk.jam_selesai
    FROM jadwal j
    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id
    JOIN jam_kuliah jk
        ON j.id_jam = jk.id_jam
    WHERE dm.id_dosen = '$id_dosen'
    AND j.id_hari = '$id_hari'
    AND j.id_jam = '$id_jam_awal'
    LIMIT 1
");


if (mysqli_num_rows($cek_dosen_awal) > 0) {

    $data_bentrok = mysqli_fetch_assoc($cek_dosen_awal);

    $jam_mulai = substr($data_bentrok['jam_mulai'], 0, 5);
    $jam_selesai = substr($data_bentrok['jam_selesai'], 0, 5);

    echo "
    <script>

        alert(
            'Dosen $nama_dosen sudah memiliki jadwal pada $nama_hari pukul $jam_mulai - $jam_selesai.'
        );

        window.location='tambah.php';

    </script>
    ";

    exit;
}


/*
==================================================
7. CEK BENTROK KELAS PADA SESI YANG DIPILIH
==================================================
*/

$cek_kelas_awal = mysqli_query($conn, "
    SELECT
        j.id_jadwal,
        jk.jam_mulai,
        jk.jam_selesai
    FROM jadwal j
    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id
    JOIN jam_kuliah jk
        ON j.id_jam = jk.id_jam
    WHERE dm.id_kelas = '$id_kelas'
    AND j.id_hari = '$id_hari'
    AND j.id_jam = '$id_jam_awal'
    LIMIT 1
");


if (mysqli_num_rows($cek_kelas_awal) > 0) {

    $data_bentrok = mysqli_fetch_assoc($cek_kelas_awal);

    $jam_mulai = substr($data_bentrok['jam_mulai'], 0, 5);
    $jam_selesai = substr($data_bentrok['jam_selesai'], 0, 5);

    echo "
    <script>

        alert(
            'Kelas $nama_kelas - $nama_mk sudah memiliki jadwal pada $nama_hari pukul $jam_mulai - $jam_selesai.'
        );

        window.location='tambah.php';

    </script>
    ";

    exit;
}


/*
==================================================
8. AMBIL SEMUA SESI MULAI DARI SESI PILIHAN
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


/*
==================================================
9. CARI SESI YANG TERSEDIA
==================================================
*/

while ($jam = mysqli_fetch_assoc($query_jam)) {

    $id_jam = $jam['id_jam'];

    $jam_mulai = substr($jam['jam_mulai'], 0, 5);
    $jam_selesai = substr($jam['jam_selesai'], 0, 5);


    /*
    ==============================================
    CEK DOSEN LAGI UNTUK SESI BERIKUTNYA
    ==============================================
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

        /*
        ------------------------------------------
        Kalau sesi berikutnya juga bentrok dosen,
        jangan lanjut mencari.
        ------------------------------------------
        */

        if ($id_jam != $id_jam_awal) {

            echo "
            <script>

                alert(
                    'Dosen $nama_dosen sudah memiliki jadwal pada $nama_hari pukul $jam_mulai - $jam_selesai. Tidak dapat memindahkan jadwal ke sesi tersebut.'
                );

                window.location='tambah.php';

            </script>
            ";

            exit;
        }

        continue;
    }


    /*
    ==============================================
    CEK KELAS
    ==============================================
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

        if ($id_jam != $id_jam_awal) {

            echo "
            <script>

                alert(
                    'Kelas $nama_kelas sudah memiliki jadwal pada $nama_hari pukul $jam_mulai - $jam_selesai.'
                );

                window.location='tambah.php';

            </script>
            ";

            exit;
        }

        continue;
    }


    /*
    ==============================================
    CEK KUOTA SEMESTER
    ==============================================
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


    /*
    ==============================================
    KALAU KUOTA PENUH
    LANJUT KE SESI BERIKUTNYA
    ==============================================
    */

    if ($jumlah_semester >= $max_kelas) {

        continue;
    }


    /*
    ==============================================
    CARI RUANGAN KOSONG
    ==============================================
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


    /*
    ==============================================
    KALAU SEMUA RUANGAN PENUH
    LANJUT KE SESI BERIKUTNYA
    ==============================================
    */

    if (mysqli_num_rows($query_ruangan) == 0) {

        continue;
    }


    /*
    ==============================================
    AMBIL RUANGAN
    ==============================================
    */

    $data_ruangan = mysqli_fetch_assoc($query_ruangan);

    $id_ruangan = $data_ruangan['id_ruangan'];


/*
==================================================
10. CARI RELASI DOSEN - MK - KELAS
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


    if (mysqli_num_rows($query_dm) > 0) {

        $data_dm = mysqli_fetch_assoc($query_dm);

        $id_dosen_mk = $data_dm['id'];

    } else {

        $insert_dm = mysqli_query($conn, "
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


        if (!$insert_dm) {

            echo "
            <script>
                alert('Gagal menyimpan data dosen mengajar.');
                window.location='tambah.php';
            </script>
            ";

            exit;
        }

        $id_dosen_mk = mysqli_insert_id($conn);
    }


/*
==================================================
11. SIMPAN JADWAL
==================================================
*/

    $insert_jadwal = mysqli_query($conn, "
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


    if ($insert_jadwal) {

        /*
        ==========================================
        JIKA SESI BERUBAH
        ==========================================
        */

        if ($id_jam != $id_jam_awal) {

            echo "
            <script>

                alert(
                    'Sesi awal tidak dapat digunakan karena kapasitas/ruangan penuh. Jadwal otomatis ditempatkan pada $jam_mulai - $jam_selesai.'
                );

                window.location='index.php';

            </script>
            ";

        } else {

            echo "
            <script>

                alert(
                    'Jadwal berhasil disimpan pada $nama_hari pukul $jam_mulai - $jam_selesai.'
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
12. SEMUA SESI TIDAK TERSEDIA
==================================================
*/

echo "
<script>

    alert(
        'Tidak ada sesi yang dapat digunakan untuk $nama_kelas - $nama_mk pada hari $nama_hari.'
    );

    window.location='tambah.php';

</script>
";

exit;

?>
