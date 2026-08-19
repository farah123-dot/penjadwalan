<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

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
3. AMBIL DATA KELAS DIBUKA
==================================================
*/

$query_kelas = mysqli_query($conn, "
    SELECT
        kd.id_kelas_dibuka,
        kd.id_kelas,
        kd.id_mk,
        k.nama_kelas,
        mk.kode_mk,
        mk.nama_mk,
        mk.semester
    FROM kelas_dibuka kd

    INNER JOIN kelas k
        ON kd.id_kelas = k.id_kelas

    INNER JOIN mata_kuliah mk
        ON kd.id_mk = mk.id_mk

    WHERE kd.id_kelas_dibuka = '$id_kelas_dibuka'

    LIMIT 1
");

if (!$query_kelas || mysqli_num_rows($query_kelas) == 0) {

    echo "
    <script>
        alert('Kelas dan mata kuliah yang dipilih tidak ditemukan.');
        window.location='tambah.php';
    </script>
    ";

    exit;
}

$data_kelas = mysqli_fetch_assoc($query_kelas);

$id_kelas   = $data_kelas['id_kelas'];
$id_mk      = $data_kelas['id_mk'];
$semester   = $data_kelas['semester'];
$nama_kelas = $data_kelas['nama_kelas'];
$kode_mk    = $data_kelas['kode_mk'];
$nama_mk    = $data_kelas['nama_mk'];

/*
==================================================
4. AMBIL NAMA DOSEN
==================================================
*/

$query_dosen = mysqli_query($conn, "
    SELECT nama_dosen
    FROM dosen
    WHERE id_dosen = '$id_dosen'
    LIMIT 1
");

$data_dosen = mysqli_fetch_assoc($query_dosen);

$nama_dosen = $data_dosen['nama_dosen'] ?? 'Dosen';

/*
==================================================
5. AMBIL NAMA HARI
==================================================
*/

$query_hari = mysqli_query($conn, "
    SELECT nama_hari
    FROM hari
    WHERE id_hari = '$id_hari'
    LIMIT 1
");

$data_hari = mysqli_fetch_assoc($query_hari);

$nama_hari = $data_hari['nama_hari'] ?? 'Hari';

/*
==================================================
6. PENGATURAN KAMPUS
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

/*
==================================================
7. AMBIL SEMUA SESI
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

if (!$query_jam || mysqli_num_rows($query_jam) == 0) {

    echo "
    <script>
        alert('Data sesi/jam kuliah tidak ditemukan.');
        window.location='tambah.php';
    </script>
    ";

    exit;
}

/*
==================================================
8. CARI SESI YANG TERSEDIA
==================================================
*/

while ($jam = mysqli_fetch_assoc($query_jam)) {

    $id_jam = $jam['id_jam'];

    $jam_mulai   = substr($jam['jam_mulai'], 0, 5);
    $jam_selesai = substr($jam['jam_selesai'], 0, 5);

    /*
    ==================================================
    8A. CEK DOSEN
    ==================================================
    */

    $cek_dosen = mysqli_query($conn, "
        SELECT
            j.id_jadwal
        FROM jadwal j

        INNER JOIN dosen_mk dm
            ON j.id_dosen_mk = dm.id

        WHERE
            dm.id_dosen = '$id_dosen'
            AND j.id_hari = '$id_hari'
            AND j.id_jam = '$id_jam'

        LIMIT 1
    ");

    if (mysqli_num_rows($cek_dosen) > 0) {

        /*
        Jika sesi pertama bentrok,
        lanjut cari sesi berikutnya.
        */

        continue;
    }

    /*
    ==================================================
    8B. CEK KELAS
    ==================================================
    */

    $cek_kelas = mysqli_query($conn, "
        SELECT
            j.id_jadwal
        FROM jadwal j

        INNER JOIN dosen_mk dm
            ON j.id_dosen_mk = dm.id

        WHERE
            dm.id_kelas = '$id_kelas'
            AND j.id_hari = '$id_hari'
            AND j.id_jam = '$id_jam'

        LIMIT 1
    ");

    if (mysqli_num_rows($cek_kelas) > 0) {

        continue;
    }

    /*
    ==================================================
    8C. CEK JUMLAH KELAS PER SEMESTER
    ==================================================
    */

    $cek_semester = mysqli_query($conn, "
        SELECT COUNT(*) AS jumlah
        FROM jadwal j

        INNER JOIN dosen_mk dm
            ON j.id_dosen_mk = dm.id

        INNER JOIN mata_kuliah mk
            ON dm.id_mk = mk.id_mk

        WHERE
            mk.semester = '$semester'
            AND j.id_hari = '$id_hari'
            AND j.id_jam = '$id_jam'
    ");

    $data_semester = mysqli_fetch_assoc($cek_semester);

    $jumlah_semester = (int) ($data_semester['jumlah'] ?? 0);

    if ($jumlah_semester >= $max_kelas) {

        continue;
    }

    /*
    ==================================================
    8D. CARI RUANGAN KOSONG
    ==================================================
    */

    $query_ruangan = mysqli_query($conn, "
        SELECT
            r.id_ruangan,
            r.nama_ruangan
        FROM ruangan r

        WHERE NOT EXISTS (

            SELECT 1
            FROM jadwal j

            WHERE
                j.id_ruangan = r.id_ruangan
                AND j.id_hari = '$id_hari'
                AND j.id_jam = '$id_jam'

        )

        ORDER BY r.id_ruangan ASC

        LIMIT 1
    ");

    if (!$query_ruangan || mysqli_num_rows($query_ruangan) == 0) {

        continue;
    }

    /*
    ==================================================
    8E. AMBIL RUANGAN
    ==================================================
    */

    $data_ruangan = mysqli_fetch_assoc($query_ruangan);

    $id_ruangan   = $data_ruangan['id_ruangan'];
    $nama_ruangan = $data_ruangan['nama_ruangan'];

    /*
    ==================================================
    9. CARI RELASI DOSEN - MK - KELAS
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

        /*
        ==============================================
        BUAT RELASI BARU
        ==============================================
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
                alert('Gagal membuat relasi dosen, mata kuliah, dan kelas.');
                window.location='tambah.php';
            </script>
            ";

            exit;
        }

        $id_dosen_mk = mysqli_insert_id($conn);
    }

    /*
    ==================================================
    10. SIMPAN JADWAL
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

    /*
    ==================================================
    11. HASIL
    ==================================================
    */

    if ($insert_jadwal) {

        if ($id_jam == $id_jam_awal) {

            echo "
            <script>

                alert(
                    'Jadwal berhasil disimpan!\\n\\n' +
                    'Mata Kuliah: $nama_mk\\n' +
                    'Kelas: $nama_kelas\\n' +
                    'Dosen: $nama_dosen\\n' +
                    'Hari: $nama_hari\\n' +
                    'Jam: $jam_mulai - $jam_selesai\\n' +
                    'Ruangan: $nama_ruangan'
                );

                window.location='index.php';

            </script>
            ";

        } else {

            echo "
            <script>

                alert(
                    'Sesi awal tidak tersedia.\\n\\n' +
                    'Jadwal otomatis ditempatkan pada sesi $jam_mulai - $jam_selesai.\\n\\n' +
                    'Mata Kuliah: $nama_mk\\n' +
                    'Kelas: $nama_kelas\\n' +
                    'Dosen: $nama_dosen\\n' +
                    'Ruangan: $nama_ruangan'
                );

                window.location='index.php';

            </script>
            ";
        }

        exit;
    }

    /*
    Jika INSERT gagal, jangan lanjut tanpa informasi.
    */

    echo "
    <script>

        alert(
            'Gagal menyimpan jadwal.\\n\\nError: " . addslashes(mysqli_error($conn)) . "'
        );

        window.location='tambah.php';

    </script>
    ";

    exit;
}

/*
==================================================
12. SEMUA SESI TIDAK TERSEDIA
==================================================
*/

echo "
<script>

    alert(
        'Tidak ada sesi yang tersedia.\\n\\n' +
        'Kelas: $nama_kelas\\n' +
        'Mata Kuliah: $nama_mk\\n' +
        'Dosen: $nama_dosen\\n' +
        'Hari: $nama_hari\\n\\n' +
        'Kemungkinan penyebab:\\n' +
        '- Dosen sudah memiliki jadwal\\n' +
        '- Kelas sudah memiliki jadwal\\n' +
        '- Semua ruangan penuh\\n' +
        '- Kapasitas semester sudah penuh'
    );

    window.location='tambah.php';

</script>
";

exit;

?>
