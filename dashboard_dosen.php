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
AMBIL ID DOSEN DARI SESSION
==================================================
*/

$id_dosen = $_SESSION['id_dosen'] ?? 0;


/*
==================================================
AMBIL DATA DOSEN
==================================================
*/

$nama_dosen = "Dosen";

if ($id_dosen != 0) {

    $query_dosen = mysqli_query($conn, "

        SELECT
            id_dosen,
            nama_dosen

        FROM dosen

        WHERE id_dosen = '$id_dosen'

        LIMIT 1

    ");

    if ($query_dosen && mysqli_num_rows($query_dosen) > 0) {

        $data_dosen = mysqli_fetch_assoc($query_dosen);

        $nama_dosen = $data_dosen['nama_dosen'];

    }

}


/*
==================================================
JUMLAH JADWAL DOSEN
==================================================
*/

$jumlah_jadwal = 0;

if ($id_dosen != 0) {

    $query_jumlah = mysqli_query($conn, "

        SELECT COUNT(*) AS total

        FROM jadwal j

        JOIN dosen_mk dm
            ON j.id_dosen_mk = dm.id

        WHERE dm.id_dosen = '$id_dosen'

    ");

    if ($query_jumlah) {

        $hasil_jumlah = mysqli_fetch_assoc($query_jumlah);

        $jumlah_jadwal = $hasil_jumlah['total'];

    }

}


/*
==================================================
DAFTAR WARNA MATA KULIAH
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


/*
==================================================
AMBIL DATA JAM
==================================================
*/

$data_jam = mysqli_query($conn, "

    SELECT *

    FROM jam_kuliah

    ORDER BY id_jam ASC

");

?>

<style>

/* ==================================================
   DASHBOARD DOSEN
================================================== */

.dashboard-dosen {

    padding: 25px;

    width: 100%;

    box-sizing: border-box;

}


/* ==================================================
   HEADER
================================================== */

.dashboard-dosen-header {

    margin-bottom: 25px;

}

.dashboard-dosen-header h2 {

    margin: 0;

    font-size: 26px;

    font-weight: 700;

    color: #1e293b;

}

.dashboard-dosen-header p {

    margin-top: 7px;

    color: #64748b;

    font-size: 14px;

}


/* ==================================================
   CARD
================================================== */

.dashboard-dosen-card {

    display: grid;

    grid-template-columns: 300px;

    margin-bottom: 25px;

}

.dosen-card {

    background: #ffffff;

    border-radius: 14px;

    padding: 20px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.07);

    border: 1px solid #e5e7eb;

    display: flex;

    align-items: center;

    gap: 15px;

}

.dosen-card-icon {

    width: 52px;

    height: 52px;

    border-radius: 12px;

    background: #dbeafe;

    color: #2563eb;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 23px;

    flex-shrink: 0;

}

.dosen-card-content {

    min-width: 0;

}

.dosen-card-label {

    font-size: 13px;

    color: #64748b;

    margin-bottom: 4px;

}

.dosen-card-number {

    font-size: 25px;

    font-weight: 700;

    color: #1e293b;

}


/* ==================================================
   JADWAL BOX
================================================== */

.schedule-box-dosen {

    background: #ffffff;

    border-radius: 14px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.07);

    border: 1px solid #e5e7eb;

    overflow: hidden;

}


/* ==================================================
   HEADER JADWAL
================================================== */

.schedule-header-dosen {

    padding: 18px 20px;

    border-bottom: 1px solid #e5e7eb;

    display: flex;

    justify-content: space-between;

    align-items: center;

}

.schedule-header-dosen h3 {

    margin: 0;

    font-size: 18px;

    color: #1e293b;

}


/* ==================================================
   BODY JADWAL
================================================== */

.schedule-body-dosen {

    padding: 20px;

    overflow-x: auto;

}


/* ==================================================
   TABLE JADWAL
================================================== */

.schedule-table-dosen {

    width: 100%;

    border-collapse: collapse;

    min-width: 900px;

    font-size: 13px;

}

.schedule-table-dosen th {

    background: #1e293b;

    color: white;

    padding: 12px 10px;

    border: 1px solid #334155;

    white-space: nowrap;

}

.schedule-table-dosen td {

    border: 1px solid #e5e7eb;

    padding: 10px;

    text-align: center;

    vertical-align: middle;

}


/* ==================================================
   HARI
================================================== */

.schedule-table-dosen .hari {

    background: #f8fafc;

    font-weight: 700;

    color: #334155;

    min-width: 100px;

}


/* ==================================================
   JAM
================================================== */

.schedule-table-dosen .jam {

    background: #f8fafc;

    white-space: nowrap;

    font-weight: 500;

    min-width: 120px;

}


/* ==================================================
   JADWAL CELL
================================================== */

.jadwal-cell-dosen {

    color: white;

    border-radius: 5px;

    padding: 8px !important;

}

.jadwal-cell-dosen b {

    font-size: 12px;

}

.jadwal-cell-dosen small {

    font-size: 11px;

}


/* ==================================================
   CELL KOSONG
================================================== */

.cell-kosong-dosen {

    color: #94a3b8;

    background: #ffffff;

}


/* ==================================================
   PESAN BELUM ADA JADWAL
================================================== */

.belum-ada-jadwal {

    padding: 30px !important;

    color: #64748b;

}


/* ==================================================
   RESPONSIVE
================================================== */

@media (max-width: 768px) {

    .dashboard-dosen {

        padding: 15px;

    }

    .dashboard-dosen-header h2 {

        font-size: 22px;

    }

    .schedule-header-dosen {

        flex-direction: column;

        align-items: flex-start;

        gap: 10px;

    }

}

</style>


<div class="dashboard-dosen">


    <!-- ==================================================
         HEADER
    ================================================== -->

    <div class="dashboard-dosen-header">

        <h2>

            Dashboard Dosen

        </h2>

        <p>

            Selamat datang,

            <strong>

                <?= htmlspecialchars($nama_dosen); ?>

            </strong>

        </p>

    </div>


    <!-- ==================================================
         CARD
    ================================================== -->

    <div class="dashboard-dosen-card">


        <div class="dosen-card">


            <div class="dosen-card-icon">

                📅

            </div>


            <div class="dosen-card-content">

                <div class="dosen-card-label">

                    Jumlah Jadwal Mengajar

                </div>


                <div class="dosen-card-number">

                    <?= $jumlah_jadwal; ?>

                </div>

            </div>


        </div>


    </div>


    <!-- ==================================================
         JADWAL PERKULIAHAN
    ================================================== -->

    <div class="schedule-box-dosen">


        <div class="schedule-header-dosen">

            <h3>

                Jadwal Perkuliahan

            </h3>

        </div>


        <div class="schedule-body-dosen">


            <table class="schedule-table-dosen">


                <!-- ==================================================
                     HEADER TABLE
                ================================================== -->

                <thead>

                    <tr>


                        <th width="120">

                            Hari

                        </th>


                        <th width="120">

                            Waktu

                        </th>


                        <?php

                        /*
                        ==============================================
                        HEADER RUANGAN
                        ==============================================
                        */

                        if ($data_ruangan) {

                            while ($ruang_header = mysqli_fetch_assoc($data_ruangan)) {

                        ?>

                            <th>

                                <?= htmlspecialchars(
                                    $ruang_header['nama_ruangan']
                                ); ?>

                            </th>

                        <?php

                            }

                        }

                        ?>

                    </tr>

                </thead>


                <!-- ==================================================
                     BODY TABLE
                ================================================== -->

                <tbody>


                <?php


                /*
                ======================================================
                CEK APAKAH DOSEN PUNYA JADWAL
                ======================================================
                */

                if ($jumlah_jadwal == 0) {

                ?>

                    <tr>

                        <td
                            colspan="100"
                            class="belum-ada-jadwal"
                        >

                            Belum ada jadwal mengajar untuk Anda.

                        </td>

                    </tr>

                <?php

                } else {


                    /*
                    ==================================================
                    LOOP HARI
                    ==================================================
                    */

                    if ($data_hari) {

                        while ($hari = mysqli_fetch_assoc($data_hari)) {


                            /*
                            ==========================================
                            AMBIL JAM UNTUK SETIAP HARI
                            ==========================================
                            */

                            $data_jam_hari = mysqli_query($conn, "

                                SELECT *

                                FROM jam_kuliah

                                ORDER BY id_jam ASC

                            ");


                            $jumlah_jam_hari = mysqli_num_rows(
                                $data_jam_hari
                            );


                            $first = true;


                            /*
                            ==========================================
                            LOOP JAM
                            ==========================================
                            */

                            while ($jam = mysqli_fetch_assoc(
                                $data_jam_hari
                            )) {

                ?>

                                <tr>


                                    <?php

                                    /*
                                    ==================================
                                    NAMA HARI
                                    ==================================
                                    */

                                    if ($first) {

                                    ?>

                                        <td

                                            rowspan="<?= $jumlah_jam_hari; ?>"

                                            class="hari"

                                        >

                                            <?= htmlspecialchars(
                                                $hari['nama_hari']
                                            ); ?>

                                        </td>

                                    <?php

                                        $first = false;

                                    }

                                    ?>


                                    <!-- ==============================
                                         JAM
                                    =============================== -->

                                    <td class="jam">

                                        <?= htmlspecialchars(
                                            substr(
                                                $jam['jam_mulai'],
                                                0,
                                                5
                                            )
                                        ); ?>

                                        -

                                        <?= htmlspecialchars(
                                            substr(
                                                $jam['jam_selesai'],
                                                0,
                                                5
                                            )
                                        ); ?>

                                    </td>


                                    <?php


                                    /*
                                    ==================================
                                    RUANGAN
                                    ==================================
                                    */

                                    $data_ruangan2 = mysqli_query(
                                        $conn,
                                        "

                                        SELECT *

                                        FROM ruangan

                                        ORDER BY id_ruangan ASC

                                        "
                                    );


                                    while (
                                        $ruang = mysqli_fetch_assoc(
                                            $data_ruangan2
                                        )
                                    ) {


                                        $id_hari =
                                            $hari['id_hari'];

                                        $id_jam =
                                            $jam['id_jam'];

                                        $id_ruangan =
                                            $ruang['id_ruangan'];


                                        /*
                                        ==================================
                                        CARI JADWAL DOSEN
                                        ==================================
                                        */

                                        $query_jadwal = mysqli_query(
                                            $conn,
                                            "

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

                                                j.id_hari = '$id_hari'

                                                AND j.id_jam = '$id_jam'

                                                AND j.id_ruangan = '$id_ruangan'

                                                AND dm.id_dosen = '$id_dosen'

                                            LIMIT 1

                                            "
                                        );


                                        /*
                                        ==================================
                                        ADA JADWAL
                                        ==================================
                                        */

                                        if (
                                            $query_jadwal &&
                                            mysqli_num_rows(
                                                $query_jadwal
                                            ) > 0
                                        ) {


                                            $data = mysqli_fetch_assoc(
                                                $query_jadwal
                                            );


                                            $nama_mk =
                                                $data['nama_mk'];


                                            /*
                                            ==================================
                                            WARNA MATA KULIAH
                                            ==================================
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

                                                class="jadwal-cell-dosen"

                                                style="
                                                    background-color:
                                                    <?= $warna; ?>;
                                                "

                                            >

                                                <b>

                                                    <?= htmlspecialchars(
                                                        $data['nama_mk']
                                                    ); ?>

                                                </b>


                                                <br>


                                                <small>

                                                    <?= htmlspecialchars(
                                                        $data['kode_mk']
                                                    ); ?>

                                                </small>


                                                <br>


                                                <small>

                                                    Kelas:

                                                    <?= htmlspecialchars(
                                                        $data['nama_kelas']
                                                    ); ?>

                                                </small>


                                            </td>


                                    <?php

                                        } else {

                                    ?>


                                            <td class="cell-kosong-dosen">

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
