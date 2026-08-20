<?php

require_once "koneksi.php";
require_once "auth/cek_login.php";

include "layout/header.php";
include "layout/sidebar_dosen.php";


/*
==================================================
TIMEZONE
==================================================
*/

date_default_timezone_set('Asia/Jakarta');


/*
==================================================
AMBIL ID DOSEN YANG LOGIN
==================================================
*/

$id_dosen = $_SESSION['id_dosen'] ?? '';


/*
==================================================
VALIDASI DOSEN
==================================================
*/

if ($id_dosen == '') {

    echo "
    <script>
        alert('Data dosen yang login tidak ditemukan.');
        window.location='index.php';
    </script>
    ";

    exit;
}


/*
==================================================
AMBIL DATA DOSEN
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


if (!$data_dosen) {

    echo "
    <script>
        alert('Data dosen tidak ditemukan.');
        window.location='index.php';
    </script>
    ";

    exit;
}


$nama_dosen = $data_dosen['nama_dosen'];


/*
==================================================
JUMLAH MATA KULIAH DOSEN
==================================================
*/

$query_mk = mysqli_query($conn, "

    SELECT COUNT(DISTINCT dm.id_mk) AS total

    FROM dosen_mk dm

    JOIN jadwal j
        ON j.id_dosen_mk = dm.id

    WHERE dm.id_dosen = '$id_dosen'

");


$data_mk = mysqli_fetch_assoc($query_mk);

$jumlah_mk = $data_mk['total'] ?? 0;


/*
==================================================
JUMLAH KELAS DOSEN
==================================================
*/

$query_kelas = mysqli_query($conn, "

    SELECT COUNT(DISTINCT dm.id_kelas) AS total

    FROM dosen_mk dm

    JOIN jadwal j
        ON j.id_dosen_mk = dm.id

    WHERE dm.id_dosen = '$id_dosen'

");


$data_kelas = mysqli_fetch_assoc($query_kelas);

$jumlah_kelas = $data_kelas['total'] ?? 0;


/*
==================================================
JUMLAH JADWAL DOSEN
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

$jumlah_jadwal = $data_jadwal['total'] ?? 0;


/*
==================================================
JUMLAH HARI MENGAJAR
==================================================
*/

$query_hari = mysqli_query($conn, "

    SELECT COUNT(DISTINCT j.id_hari) AS total

    FROM jadwal j

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    WHERE dm.id_dosen = '$id_dosen'

");


$data_hari = mysqli_fetch_assoc($query_hari);

$jumlah_hari = $data_hari['total'] ?? 0;


/*
==================================================
WARNA MATA KULIAH
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


/*
==================================================
AMBIL DATA RUANGAN
==================================================
*/

$data_ruangan = mysqli_query($conn, "

    SELECT *

    FROM ruangan

    ORDER BY id_ruangan ASC

");


/*
==================================================
AMBIL DATA HARI
==================================================
*/

$data_hari = mysqli_query($conn, "

    SELECT *

    FROM hari

    ORDER BY id_hari ASC

");

?>


<style>

/* =================================================
   DASHBOARD DOSEN
================================================= */

.dashboard-dosen {

    padding: 25px;

    width: 100%;

    box-sizing: border-box;

}


/* =================================================
   HEADER
================================================= */

.dashboard-header {

    margin-bottom: 25px;

}

.dashboard-header h2 {

    margin: 0;

    font-size: 26px;

    font-weight: 700;

    color: #1e293b;

}

.dashboard-header p {

    margin-top: 6px;

    color: #64748b;

    font-size: 14px;

}


/* =================================================
   CARD STATISTIK
================================================= */

.statistik-grid {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 18px;

    margin-bottom: 30px;

}


.statistik-card {

    background: white;

    border: 1px solid #e5e7eb;

    border-radius: 14px;

    padding: 20px;

    display: flex;

    align-items: center;

    gap: 15px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.07);

    transition: 0.2s ease;

}


.statistik-card:hover {

    transform: translateY(-3px);

    box-shadow: 0 7px 20px rgba(0,0,0,0.10);

}


/* =================================================
   ICON STATISTIK
================================================= */

.statistik-icon {

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

    background: #fee2e2;

}

.icon-hari {

    background: #fef3c7;

}


/* =================================================
   ISI STATISTIK
================================================= */

.statistik-label {

    font-size: 13px;

    color: #64748b;

    margin-bottom: 4px;

}

.statistik-number {

    font-size: 25px;

    font-weight: 700;

    color: #1e293b;

}


/* =================================================
   CARD JADWAL
================================================= */

.jadwal-card {

    background: #ffffff;

    border-radius: 14px;

    border: 1px solid #e5e7eb;

    box-shadow: 0 4px 15px rgba(0,0,0,0.07);

    overflow: hidden;

}


/* =================================================
   HEADER JADWAL
================================================= */

.jadwal-header {

    padding: 18px 20px;

    border-bottom: 1px solid #e5e7eb;

}

.jadwal-header h3 {

    margin: 0;

    font-size: 19px;

    font-weight: 700;

    color: #1e293b;

}

.jadwal-header p {

    margin: 5px 0 0;

    color: #64748b;

    font-size: 13px;

}


/* =================================================
   AREA SCROLL JADWAL
================================================= */

.jadwal-scroll {

    max-height: 600px;

    overflow-x: auto;

    overflow-y: auto;

}


/* =================================================
   TABLE
================================================= */

.jadwal-table {

    width: 100%;

    min-width: 1100px;

    border-collapse: collapse;

    font-size: 13px;

}


/* =================================================
   HEADER TABLE
================================================= */

.jadwal-table th {

    background: #1e293b;

    color: white;

    padding: 13px 10px;

    border: 1px solid #334155;

    white-space: nowrap;

    position: sticky;

    top: 0;

    z-index: 5;

}


/* =================================================
   BODY TABLE
================================================= */

.jadwal-table td {

    border: 1px solid #e5e7eb;

    padding: 10px;

    text-align: center;

    vertical-align: middle;

}


/* =================================================
   HARI
================================================= */

.jadwal-table .kolom-hari {

    background: #f8fafc;

    font-weight: 700;

    color: #334155;

    min-width: 100px;

}


/* =================================================
   JAM
================================================= */

.jadwal-table .kolom-jam {

    background: #f8fafc;

    font-weight: 600;

    white-space: nowrap;

    min-width: 130px;

}


/* =================================================
   JADWAL TERISI
================================================= */

.jadwal-cell {

    color: white;

    min-width: 160px;

    border-radius: 4px;

}

.jadwal-cell b {

    font-size: 12px;

}

.jadwal-cell small {

    font-size: 11px;

}


/* =================================================
   CELL KOSONG
================================================= */

.cell-kosong {

    color: #94a3b8;

    min-width: 160px;

}


/* =================================================
   SCROLLBAR
================================================= */

.jadwal-scroll::-webkit-scrollbar {

    width: 10px;

    height: 10px;

}

.jadwal-scroll::-webkit-scrollbar-track {

    background: #f1f5f9;

}

.jadwal-scroll::-webkit-scrollbar-thumb {

    background: #94a3b8;

    border-radius: 10px;

}

.jadwal-scroll::-webkit-scrollbar-thumb:hover {

    background: #64748b;

}


/* =================================================
   RESPONSIVE
================================================= */

@media (max-width: 1200px) {

    .statistik-grid {

        grid-template-columns: repeat(2, 1fr);

    }

}


@media (max-width: 768px) {

    .dashboard-dosen {

        padding: 15px;

    }

    .statistik-grid {

        grid-template-columns: 1fr;

    }

    .dashboard-header h2 {

        font-size: 22px;

    }

}

</style>


<div class="dashboard-dosen">


    <!-- =================================================
         HEADER DASHBOARD
    ================================================= -->

    <div class="dashboard-header">

        <h2>
            Dashboard Dosen
        </h2>

        <p>
            Selamat datang, <strong><?= htmlspecialchars($nama_dosen); ?></strong>
        </p>

    </div>


    <!-- =================================================
         CARD STATISTIK
    ================================================= -->

    <div class="statistik-grid">


        <!-- MATA KULIAH -->

        <div class="statistik-card">

            <div class="statistik-icon icon-mk">
                📚
            </div>

            <div>

                <div class="statistik-label">
                    Mata Kuliah
                </div>

                <div class="statistik-number">
                    <?= $jumlah_mk; ?>
                </div>

            </div>

        </div>


        <!-- KELAS -->

        <div class="statistik-card">

            <div class="statistik-icon icon-kelas">
                🏫
            </div>

            <div>

                <div class="statistik-label">
                    Kelas
                </div>

                <div class="statistik-number">
                    <?= $jumlah_kelas; ?>
                </div>

            </div>

        </div>


        <!-- JADWAL -->

        <div class="statistik-card">

            <div class="statistik-icon icon-jadwal">
                📅
            </div>

            <div>

                <div class="statistik-label">
                    Total Jadwal
                </div>

                <div class="statistik-number">
                    <?= $jumlah_jadwal; ?>
                </div>

            </div>

        </div>


        <!-- HARI -->

        <div class="statistik-card">

            <div class="statistik-icon icon-hari">
                🗓️
            </div>

            <div>

                <div class="statistik-label">
                    Hari Mengajar
                </div>

                <div class="statistik-number">
                    <?= $jumlah_hari; ?>
                </div>

            </div>

        </div>


    </div>


    <!-- =================================================
         CARD JADWAL
    ================================================= -->

    <div class="jadwal-card">


        <div class="jadwal-header">

            <h3>
                📅 Jadwal Perkuliahan
            </h3>

            <p>
                Jadwal mengajar Anda
            </p>

        </div>


        <!-- =================================================
             AREA SCROLL
        ================================================= -->

        <div class="jadwal-scroll">


            <table class="jadwal-table">


                <thead>

                    <tr>

                        <th>
                            Hari
                        </th>

                        <th>
                            Waktu
                        </th>


                        <?php

                        /*
                        ==========================================
                        HEADER RUANGAN
                        ==========================================
                        */

                        mysqli_data_seek($data_ruangan, 0);

                        while ($ruang = mysqli_fetch_assoc($data_ruangan)) {

                        ?>

                            <th>

                                <?= htmlspecialchars(
                                    $ruang['nama_ruangan']
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
                ==========================================
                LOOP HARI
                ==========================================
                */

                mysqli_data_seek($data_hari, 0);

                while ($hari = mysqli_fetch_assoc($data_hari)) {


                    /*
                    ======================================
                    AMBIL JAM
                    ======================================
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


                        <?php if ($baris_pertama) { ?>


                            <td
                                class="kolom-hari"
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


                        <!-- ==============================
                             JAM
                        =============================== -->

                        <td class="kolom-jam">

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
                        RUANGAN
                        ==================================
                        */

                        mysqli_data_seek($data_ruangan, 0);

                        while (
                            $ruang =
                            mysqli_fetch_assoc($data_ruangan)
                        ) {


                            $id_hari =
                                $hari['id_hari'];

                            $id_jam =
                                $jam['id_jam'];

                            $id_ruangan =
                                $ruang['id_ruangan'];


                            /*
                            ==============================
                            CARI JADWAL DOSEN
                            ==============================
                            */

                            $query = mysqli_query($conn, "

                                SELECT

                                    mk.nama_mk,

                                    mk.kode_mk,

                                    k.nama_kelas,

                                    d.nama_dosen

                                FROM jadwal j

                                JOIN dosen_mk dm
                                    ON j.id_dosen_mk = dm.id

                                JOIN mata_kuliah mk
                                    ON dm.id_mk = mk.id_mk

                                JOIN kelas k
                                    ON dm.id_kelas = k.id_kelas

                                JOIN dosen d
                                    ON dm.id_dosen = d.id_dosen

                                WHERE

                                    j.id_hari =
                                    '$id_hari'

                                    AND

                                    j.id_jam =
                                    '$id_jam'

                                    AND

                                    j.id_ruangan =
                                    '$id_ruangan'

                                    AND

                                    dm.id_dosen =
                                    '$id_dosen'

                                LIMIT 1

                            ");


                            /*
                            ==============================
                            JIKA ADA JADWAL
                            ==============================
                            */

                            if (
                                mysqli_num_rows($query)
                                > 0
                            ) {


                                $data =
                                    mysqli_fetch_assoc(
                                        $query
                                    );


                                $nama_mk =
                                    $data['nama_mk'];


                                /*
                                ==========================
                                WARNA MK
                                ==========================
                                */

                                if (
                                    !isset(
                                        $warna_mk[$nama_mk]
                                    )
                                ) {

                                    $warna_mk[$nama_mk] =
                                        $daftar_warna[
                                            $index_warna %
                                            count(
                                                $daftar_warna
                                            )
                                        ];

                                    $index_warna++;

                                }


                                $warna =
                                    $warna_mk[$nama_mk];

                        ?>

                                <td
                                    class="jadwal-cell"
                                    style="
                                        background-color:
                                        <?= $warna; ?>;
                                    "
                                >

                                    <b>

                                        <?= htmlspecialchars(
                                            $data['kode_mk']
                                        ); ?>

                                        <br>

                                        <?= htmlspecialchars(
                                            $data['nama_mk']
                                        ); ?>

                                    </b>

                                    <br>

                                    <small>

                                        <?= htmlspecialchars(
                                            $data['nama_kelas']
                                        ); ?>

                                    </small>

                                    <br>

                                    <small>

                                        <?= htmlspecialchars(
                                            $data['nama_dosen']
                                        ); ?>

                                    </small>

                                </td>


                        <?php

                            } else {

                        ?>


                                <!-- ==========================
                                     KOSONG
                                =========================== -->

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
