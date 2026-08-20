<?php

require_once "koneksi.php";
require_once "auth/cek_login.php";

include "layout/header.php";
include "layout/sidebar_dosen.php";

date_default_timezone_set('Asia/Jakarta');

/*
==================================================
AMBIL DATA DOSEN YANG LOGIN
==================================================
*/

$id_dosen = $_SESSION['id_dosen'] ?? '';

/*
Jika session id_dosen belum ada,
coba ambil berdasarkan username/nama login.
*/

if ($id_dosen == '') {

    $username = $_SESSION['username'] ?? '';
    $nama_login = $_SESSION['nama_dosen'] ?? '';

    if ($username != '') {

        $cek_dosen = mysqli_query($conn, "
            SELECT id_dosen, nama_dosen
            FROM dosen
            WHERE username = '$username'
            LIMIT 1
        ");

        if ($cek_dosen && mysqli_num_rows($cek_dosen) > 0) {

            $d = mysqli_fetch_assoc($cek_dosen);

            $id_dosen = $d['id_dosen'];

        }

    } elseif ($nama_login != '') {

        $cek_dosen = mysqli_query($conn, "
            SELECT id_dosen
            FROM dosen
            WHERE nama_dosen = '$nama_login'
            LIMIT 1
        ");

        if ($cek_dosen && mysqli_num_rows($cek_dosen) > 0) {

            $d = mysqli_fetch_assoc($cek_dosen);

            $id_dosen = $d['id_dosen'];

        }

    }
}


/*
==================================================
JIKA ID DOSEN TIDAK DITEMUKAN
==================================================
*/

if ($id_dosen == '') {

    echo "

    <div class='dashboard-error'>

        <h3>Data dosen tidak ditemukan</h3>

        <p>
            Akun yang sedang login belum terhubung dengan data dosen.
        </p>

    </div>

    ";

    include "layout/footer.php";

    exit;
}


/*
==================================================
AMBIL NAMA DOSEN
==================================================
*/

$query_dosen = mysqli_query($conn, "

    SELECT
        id_dosen,
        nama_dosen

    FROM dosen

    WHERE id_dosen = '$id_dosen'

    LIMIT 1

");

$data_dosen = mysqli_fetch_assoc($query_dosen);

$nama_dosen = $data_dosen['nama_dosen'] ?? 'Dosen';


/*
==================================================
JUMLAH MATA KULIAH
==================================================
*/

$query_mk = mysqli_query($conn, "

    SELECT COUNT(DISTINCT dm.id_mk) AS total

    FROM jadwal j

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    WHERE dm.id_dosen = '$id_dosen'

");

$data_mk = mysqli_fetch_assoc($query_mk);

$total_mk = $data_mk['total'] ?? 0;


/*
==================================================
JUMLAH KELAS
==================================================
*/

$query_kelas = mysqli_query($conn, "

    SELECT COUNT(DISTINCT dm.id_kelas) AS total

    FROM jadwal j

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    WHERE dm.id_dosen = '$id_dosen'

");

$data_kelas = mysqli_fetch_assoc($query_kelas);

$total_kelas = $data_kelas['total'] ?? 0;


/*
==================================================
JUMLAH JADWAL
==================================================
*/

$query_jadwal = mysqli_query($conn, "

    SELECT COUNT(*) AS total

    FROM jadwal j

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    WHERE dm.id_dosen = '$id_dosen'

");

$data_jadwal = mysqli_fetch_assoc($query_jadwal);

$total_jadwal = $data_jadwal['total'] ?? 0;


/*
==================================================
WARNA CARD
==================================================
*/

$daftar_warna = [

    "#2563eb",
    "#16a34a",
    "#dc2626",
    "#ca8a04",
    "#7c3aed",
    "#0891b2",
    "#ea580c",
    "#9333ea"

];

$warna_mk = [];

$index_warna = 0;

?>


<style>

/* =========================================
   DASHBOARD DOSEN
========================================= */

.dashboard-dosen {

    padding: 25px;

    width: 100%;

    box-sizing: border-box;

}


/* =========================================
   JUDUL
========================================= */

.dashboard-dosen-title {

    margin-bottom: 25px;

}

.dashboard-dosen-title h2 {

    margin: 0;

    font-size: 26px;

    font-weight: 700;

    color: #1e293b;

}

.dashboard-dosen-title p {

    margin-top: 6px;

    color: #64748b;

    font-size: 14px;

}


/* =========================================
   CARD STATISTIK
========================================= */

.dosen-statistik {

    display: grid;

    grid-template-columns: repeat(3, 1fr);

    gap: 18px;

    margin-bottom: 30px;

}


.dosen-card {

    background: white;

    border-radius: 14px;

    padding: 20px;

    border: 1px solid #e5e7eb;

    box-shadow: 0 4px 15px rgba(0,0,0,0.07);

    display: flex;

    align-items: center;

    gap: 15px;

}


.dosen-card-icon {

    width: 52px;

    height: 52px;

    border-radius: 12px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 23px;

    flex-shrink: 0;

}


.icon-mk {

    background: #dbeafe;

}

.icon-kelas {

    background: #dcfce7;

}

.icon-jadwal {

    background: #fef3c7;

}


.dosen-card-label {

    color: #64748b;

    font-size: 13px;

    margin-bottom: 4px;

}


.dosen-card-number {

    font-size: 25px;

    font-weight: 700;

    color: #1e293b;

}


/* =========================================
   BOX JADWAL
========================================= */

.jadwal-box {

    background: white;

    border-radius: 14px;

    border: 1px solid #e5e7eb;

    box-shadow: 0 4px 15px rgba(0,0,0,0.07);

    overflow: hidden;

}


/* =========================================
   HEADER JADWAL
========================================= */

.jadwal-header {

    padding: 18px 20px;

    border-bottom: 1px solid #e5e7eb;

}


.jadwal-header h3 {

    margin: 0;

    font-size: 19px;

    color: #1e293b;

}


.jadwal-header p {

    margin: 5px 0 0;

    color: #64748b;

    font-size: 13px;

}


/* =========================================
   AREA SCROLL
========================================= */

.jadwal-scroll {

    width: 100%;

    max-height: 650px;

    overflow-x: auto;

    overflow-y: auto;

}


/* =========================================
   TABLE JADWAL
========================================= */

.jadwal-table {

    border-collapse: separate;

    border-spacing: 0;

    min-width: 1100px;

    width: 100%;

}


/* =========================================
   HEADER TABLE
========================================= */

.jadwal-table th {

    background: #1e293b;

    color: white;

    padding: 12px;

    border-right: 1px solid #334155;

    border-bottom: 1px solid #334155;

    text-align: center;

    font-size: 13px;

    white-space: nowrap;

    position: sticky;

    top: 0;

    z-index: 5;

}


/* =========================================
   KOLOM HARI
========================================= */

.jadwal-table .kolom-hari {

    width: 110px;

    min-width: 110px;

}


/* =========================================
   KOLOM JAM
========================================= */

.jadwal-table .kolom-jam {

    width: 130px;

    min-width: 130px;

}


/* =========================================
   CELL
========================================= */

.jadwal-table td {

    border-right: 1px solid #e5e7eb;

    border-bottom: 1px solid #e5e7eb;

    padding: 8px;

    text-align: center;

    vertical-align: middle;

}


/* =========================================
   HARI
========================================= */

.cell-hari {

    background: #f8fafc;

    font-weight: 700;

    color: #334155;

    min-width: 110px;

}


/* =========================================
   JAM
========================================= */

.cell-jam {

    background: #f8fafc;

    color: #334155;

    font-weight: 600;

    white-space: nowrap;

    min-width: 130px;

}


/* =========================================
   CARD JADWAL
========================================= */

.jadwal-card {

    min-height: 85px;

    border-radius: 8px;

    padding: 10px 8px;

    color: white;

    display: flex;

    flex-direction: column;

    justify-content: center;

    box-sizing: border-box;

}


.jadwal-card .nama-mk {

    font-size: 13px;

    font-weight: 700;

    line-height: 1.3;

}


.jadwal-card .nama-kelas {

    font-size: 11px;

    margin-top: 5px;

}


.jadwal-card .nama-dosen {

    font-size: 10px;

    margin-top: 3px;

}


/* =========================================
   CELL KOSONG
========================================= */

.cell-kosong {

    color: #94a3b8;

    font-size: 13px;

    min-width: 130px;

    height: 85px;

}


/* =========================================
   ERROR
========================================= */

.dashboard-error {

    margin: 30px;

    padding: 25px;

    background: #fee2e2;

    border: 1px solid #fecaca;

    border-radius: 12px;

    color: #991b1b;

}


/* =========================================
   RESPONSIVE
========================================= */

@media (max-width: 1000px) {

    .dosen-statistik {

        grid-template-columns: 1fr;

    }

}


@media (max-width: 768px) {

    .dashboard-dosen {

        padding: 15px;

    }

    .dashboard-dosen-title h2 {

        font-size: 22px;

    }

}

</style>


<div class="dashboard-dosen">


    <!-- =====================================
         JUDUL
    ====================================== -->

    <div class="dashboard-dosen-title">

        <h2>
            Dashboard Dosen
        </h2>

        <p>
            Selamat datang, <strong><?= htmlspecialchars($nama_dosen); ?></strong>
        </p>

    </div>


    <!-- =====================================
         CARD STATISTIK
    ====================================== -->

    <div class="dosen-statistik">


        <!-- MATA KULIAH -->

        <div class="dosen-card">

            <div class="dosen-card-icon icon-mk">
                📚
            </div>

            <div>

                <div class="dosen-card-label">
                    Mata Kuliah
                </div>

                <div class="dosen-card-number">
                    <?= $total_mk; ?>
                </div>

            </div>

        </div>


        <!-- KELAS -->

        <div class="dosen-card">

            <div class="dosen-card-icon icon-kelas">
                🏫
            </div>

            <div>

                <div class="dosen-card-label">
                    Kelas
                </div>

                <div class="dosen-card-number">
                    <?= $total_kelas; ?>
                </div>

            </div>

        </div>


        <!-- JADWAL -->

        <div class="dosen-card">

            <div class="dosen-card-icon icon-jadwal">
                📅
            </div>

            <div>

                <div class="dosen-card-label">
                    Total Jadwal
                </div>

                <div class="dosen-card-number">
                    <?= $total_jadwal; ?>
                </div>

            </div>

        </div>


    </div>


    <!-- =====================================
         JADWAL PERKULIAHAN
    ====================================== -->

    <div class="jadwal-box">


        <div class="jadwal-header">

            <h3>
                📅 Jadwal Perkuliahan
            </h3>

            <p>
                Jadwal yang diampu oleh <?= htmlspecialchars($nama_dosen); ?>
            </p>

        </div>


        <!-- =================================
             SCROLL AREA
        ================================== -->

        <div class="jadwal-scroll">


            <table class="jadwal-table">


                <thead>

                    <tr>

                        <th class="kolom-hari">
                            Hari
                        </th>

                        <th class="kolom-jam">
                            Waktu
                        </th>


                        <?php

                        /*
                        ==================================
                        AMBIL RUANGAN
                        ==================================
                        */

                        $data_ruangan = mysqli_query($conn, "

                            SELECT *

                            FROM ruangan

                            ORDER BY id_ruangan ASC

                        ");


                        while ($ruangan = mysqli_fetch_assoc($data_ruangan)) {

                        ?>

                            <th>

                                <?= htmlspecialchars(
                                    $ruangan['nama_ruangan']
                                ); ?>

                            </th>

                        <?php

                        }

                        ?>

                    </tr>

                </thead>


                <tbody>


                    <?php

                    /*
                    ==================================
                    AMBIL HARI
                    ==================================
                    */

                    $data_hari = mysqli_query($conn, "

                        SELECT *

                        FROM hari

                        ORDER BY id_hari ASC

                    ");


                    while ($hari = mysqli_fetch_assoc($data_hari)) {


                        /*
                        ==================================
                        AMBIL JAM / SESI
                        ==================================
                        */

                        $data_jam = mysqli_query($conn, "

                            SELECT *

                            FROM jam_kuliah

                            ORDER BY id_jam ASC

                        ");


                        $jumlah_jam = mysqli_num_rows($data_jam);

                        $baris_pertama = true;


                        while ($jam = mysqli_fetch_assoc($data_jam)) {

                    ?>

                        <tr>


                            <!-- =========================
                                 HARI
                            ========================== -->

                            <?php if ($baris_pertama) { ?>

                                <td

                                    class="cell-hari"

                                    rowspan="<?= $jumlah_jam; ?>"

                                >

                                    <?= htmlspecialchars(
                                        $hari['nama_hari']
                                    ); ?>

                                </td>

                            <?php

                                $baris_pertama = false;

                            }

                            ?>


                            <!-- =========================
                                 JAM
                            ========================== -->

                            <td class="cell-jam">

                                <?= substr(
                                    $jam['jam_mulai'],
                                    0,
                                    5
                                ); ?>

                                -

                                <?= substr(
                                    $jam['jam_selesai'],
                                    0,
                                    5
                                ); ?>

                            </td>


                            <?php

                            /*
                            ==================================
                            AMBIL RUANGAN LAGI
                            ==================================
                            */

                            $data_ruangan2 = mysqli_query($conn, "

                                SELECT *

                                FROM ruangan

                                ORDER BY id_ruangan ASC

                            ");


                            while (
                                $ruang =
                                mysqli_fetch_assoc($data_ruangan2)
                            ) {


                                $id_hari_now =
                                    $hari['id_hari'];

                                $id_jam_now =
                                    $jam['id_jam'];

                                $id_ruangan_now =
                                    $ruang['id_ruangan'];


                                /*
                                ==================================
                                CARI JADWAL DOSEN
                                ==================================
                                */

                                $query_jadwal_cell = mysqli_query($conn, "

                                    SELECT

                                        mata_kuliah.nama_mk,

                                        mata_kuliah.kode_mk,

                                        dosen.nama_dosen,

                                        kelas.nama_kelas

                                    FROM jadwal

                                    JOIN dosen_mk

                                        ON jadwal.id_dosen_mk =
                                           dosen_mk.id

                                    JOIN mata_kuliah

                                        ON dosen_mk.id_mk =
                                           mata_kuliah.id_mk

                                    JOIN dosen

                                        ON dosen_mk.id_dosen =
                                           dosen.id_dosen

                                    JOIN kelas

                                        ON dosen_mk.id_kelas =
                                           kelas.id_kelas

                                    WHERE

                                        jadwal.id_hari =
                                        '$id_hari_now'

                                    AND

                                        jadwal.id_jam =
                                        '$id_jam_now'

                                    AND

                                        jadwal.id_ruangan =
                                        '$id_ruangan_now'

                                    AND

                                        dosen_mk.id_dosen =
                                        '$id_dosen'

                                    LIMIT 1

                                ");


                                /*
                                ==================================
                                JIKA ADA JADWAL
                                ==================================
                                */

                                if (
                                    mysqli_num_rows(
                                        $query_jadwal_cell
                                    ) > 0
                                ) {


                                    $data =
                                        mysqli_fetch_assoc(
                                            $query_jadwal_cell
                                        );


                                    $nama_mk =
                                        $data['nama_mk'];


                                    /*
                                    ==============================
                                    WARNA BERDASARKAN MK
                                    ==============================
                                    */

                                    if (
                                        !isset(
                                            $warna_mk[$nama_mk]
                                        )
                                    ) {

                                        $warna_mk[$nama_mk] =
                                            $daftar_warna[
                                                $index_warna %
                                                count($daftar_warna)
                                            ];

                                        $index_warna++;

                                    }


                                    $warna =
                                        $warna_mk[$nama_mk];

                                ?>

                                    <td>

                                        <div

                                            class="jadwal-card"

                                            style="
                                                background:
                                                <?= $warna; ?>;
                                            "

                                        >

                                            <div class="nama-mk">

                                                <?= htmlspecialchars(
                                                    $data['nama_mk']
                                                ); ?>

                                            </div>


                                            <div class="nama-kelas">

                                                <?= htmlspecialchars(
                                                    $data['nama_kelas']
                                                ); ?>

                                            </div>


                                            <div class="nama-dosen">

                                                <?= htmlspecialchars(
                                                    $data['nama_dosen']
                                                ); ?>

                                            </div>

                                        </div>

                                    </td>


                                <?php

                                } else {

                                ?>


                                    <!-- =====================
                                         KOSONG
                                    ====================== -->

                                    <td class="cell-kosong">

                                        -

                                    </td>


                                <?php

                                }

                            }

                            ?>


                        </tr>


                    <?php

                        }

                    }

                    ?>


                </tbody>

            </table>


        </div>

    </div>


</div>


<?php

include "layout/footer.php";

?>
