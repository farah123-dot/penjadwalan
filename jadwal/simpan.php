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
2. VALIDASI
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
        alert('Data belum lengkap!');
        window.location='tambah.php';
    </script>
    ";

    exit;
}


/*
==================================================
3. AMBIL DATA KELAS DIBUKA
==================================================
*/

$query_kelas = mysqli_query($conn, "
    SELECT
        kd.id_kelas_dibuka,
        kd.id_kelas,
        kd.id_mk,
        mk.semester,
        kd.nama_kelas,
        mk.nama_mk
    FROM kelas_dibuka kd
    JOIN mata_kuliah mk
        ON kd.id_mk = mk.id_mk
    WHERE kd.id_kelas_dibuka = '$id_kelas_dibuka'
    LIMIT 1
");


if (!$query_kelas || mysqli_num_rows($query_kelas) == 0) {

    echo "
    <script>
        alert('Data kelas tidak ditemukan!');
        window.location='tambah.php';
    </script>
    ";

    exit;
}


$data_kelas = mysqli_fetch_assoc($query_kelas);

$id_kelas = $data_kelas['id_kelas'];
$id_mk    = $data_kelas['id_mk'];
$semester = $data_kelas['semester'];


/*
==================================================
4. AMBIL PENGATURAN KAMPUS
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

$max_kelas = (int) $pengaturan['max_kelas_per_semester'];
$total_ruangan = (int) $pengaturan['total_ruangan'];


/*
==================================================
5. AMBIL SEMUA SESI MULAI DARI SESI PILIHAN
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
6. CEK SETIAP SESI
==================================================
*/

while ($jam = mysqli_fetch_assoc($query_jam)) {

    $id_jam = $jam['id_jam'];


    /*
    ==============================================
    CEK DOSEN
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
        continue;
    }


    /*
    ==============================================
    CEK JUMLAH KELAS DALAM SEMESTER
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
    KALAU RUANGAN PENUH
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
    ==============================================
    CARI DOSEN_MK
    ==============================================
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

        /*
        ==========================================
        BUAT DATA DOSEN_MK
        ==========================================
        */

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
                alert('Gagal menyimpan data dosen dan mata kuliah!');
                window.location='tambah.php';
            </script>
            ";

            exit;
        }


        $id_dosen_mk = mysqli_insert_id($conn);
    }


    /*
    ==============================================
    SIMPAN JADWAL
    ==============================================
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


    /*
    ==============================================
    BERHASIL
    ==============================================
    */

    if ($insert_jadwal) {

        $jam_mulai = substr($jam['jam_mulai'], 0, 5);
        $jam_selesai = substr($jam['jam_selesai'], 0, 5);


        if ($id_jam != $id_jam_awal) {

            echo "
            <script>

                alert(
                    'Sesi awal penuh. Jadwal otomatis dipindahkan ke sesi $jam_mulai - $jam_selesai.'
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
7. SEMUA SESI TIDAK BISA DIGUNAKAN
==================================================
*/

echo "
<script>

    alert(
        'Tidak ada sesi dan ruangan yang tersedia untuk jadwal tersebut.'
    );

    window.location='tambah.php';

</script>
";

exit;

?>
