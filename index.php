<?php

require_once "koneksi.php";


/* =========================================================
   AMBIL DATA JADWAL
========================================================= */

$query = mysqli_query($conn, "

    SELECT
        j.id_jadwal,

        h.id_hari,
        h.nama_hari,

        jk.id_jam,
        jk.jam_mulai,
        jk.jam_selesai,

        r.id_ruangan,
        r.nama_ruangan,

        k.id_kelas,
        k.nama_kelas,

        d.nama_dosen,

        mk.id_mk,
        mk.kode_mk,
        mk.nama_mk

    FROM jadwal j

    JOIN hari h
        ON j.id_hari = h.id_hari

    JOIN jam_kuliah jk
        ON j.id_jam = jk.id_jam

    JOIN ruangan r
        ON j.id_ruangan = r.id_ruangan

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    JOIN dosen d
        ON dm.id_dosen = d.id_dosen

    JOIN mata_kuliah mk
        ON dm.id_mk = mk.id_mk

    JOIN kelas k
        ON dm.id_kelas = k.id_kelas

    ORDER BY
        h.id_hari ASC,
        jk.jam_mulai ASC,
        r.id_ruangan ASC

");


/* =========================================================
   SIMPAN DATA JADWAL
========================================================= */

$jadwal = [];

if ($query) {

    while ($row = mysqli_fetch_assoc($query)) {

        $jadwal[] = $row;

    }

}


/* =========================================================
   AMBIL SEMUA HARI
========================================================= */

$hari = [];

$queryHari = mysqli_query($conn, "

    SELECT
        id_hari,
        nama_hari

    FROM hari

    ORDER BY id_hari ASC

");


if ($queryHari) {

    while ($row = mysqli_fetch_assoc($queryHari)) {

        $hari[] = $row;

    }

}


/* =========================================================
   AMBIL SEMUA JAM
========================================================= */

$jam = [];

$queryJam = mysqli_query($conn, "

    SELECT
        id_jam,
        jam_mulai,
        jam_selesai

    FROM jam_kuliah

    ORDER BY jam_mulai ASC

");


if ($queryJam) {

    while ($row = mysqli_fetch_assoc($queryJam)) {

        $jam[] = $row;

    }

}


/* =========================================================
   AMBIL SEMUA RUANGAN
========================================================= */

$ruangan = [];

$queryRuangan = mysqli_query($conn, "

    SELECT
        id_ruangan,
        nama_ruangan

    FROM ruangan

    ORDER BY id_ruangan ASC

");


if ($queryRuangan) {

    while ($row = mysqli_fetch_assoc($queryRuangan)) {

        $ruangan[] = $row;

    }

}


/* =========================================================
   BUAT INDEX JADWAL
========================================================= */

$jadwalMap = [];


foreach ($jadwal as $row) {

    $hariId =
        $row['id_hari'];

    $jamId =
        $row['id_jam'];

    $ruanganId =
        $row['id_ruangan'];


    $jadwalMap
        [$hariId]
        [$jamId]
        [$ruanganId] = $row;

}


/* =========================================================
   WARNA MATA KULIAH
========================================================= */

$warna = [

    '#0d6efd',
    '#198754',
    '#dc3545',
    '#6f42c1',
    '#fd7e14',
    '#20c997',
    '#d63384',
    '#0dcaf0',
    '#6610f2',
    '#ffc107'

];


$warnaMK = [];

$warnaIndex = 0;


foreach ($jadwal as $row) {

    $idMk =
        $row['id_mk'];


    if (!isset($warnaMK[$idMk])) {

        $warnaMK[$idMk] =
            $warna[
                $warnaIndex % count($warna)
            ];

        $warnaIndex++;

    }

}

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<title>SIKULIAH - Jadwal Perkuliahan</title>


<!-- BOOTSTRAP -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<!-- BOOTSTRAP ICONS -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">


<style>

/* =========================================================
   BODY
========================================================= */

body {

    margin: 0;

    background: #f1f5f9;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

}


/* =========================================================
   NAVBAR
========================================================= */

.navbar-custom {

    background: #ffffff;

    border-bottom: 1px solid #d0d0d0;

    min-height: 58px;

}


.logo {

    font-size: 27px;

    font-weight: 700;

    color: #0d6efd !important;

    text-decoration: none;

}


.logo i {

    margin-right: 7px;

}


/* =========================================================
   LOGIN & REGISTER
========================================================= */

.navbar-buttons {

    display: flex;

    gap: 8px;

}


.navbar-buttons .btn {

    border-radius: 8px;

    padding: 7px 16px;

    font-size: 15px;

    font-weight: 500;

}


/* =========================================================
   CARD JADWAL
========================================================= */

.schedule-card {

    width: calc(100% - 60px);

    max-width: 1200px;

    height: 650px;

    margin: 25px auto 30px;

    background: #ffffff;

    border-radius: 15px;

    border: none;

    box-shadow:
        0 10px 30px rgba(0, 0, 0, .12);

    overflow: hidden;

}


/* =========================================================
   HEADER CARD
========================================================= */

.schedule-card-header {

    height: 60px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 13px 17px;

    background: #ffffff;

    border-bottom: 1px solid #e1e5e9;

}


/* =========================================================
   JUDUL
========================================================= */

.page-title {

    font-size: 21px;

    font-weight: 500;

    color: #111827;

    margin: 0;

}


/* =========================================================
   EXPORT
========================================================= */

.btn-export {

    background: #dc3545;

    color: #ffffff !important;

    text-decoration: none;

    border: none;

    border-radius: 8px;

    padding: 7px 11px;

    font-size: 14px;

    transition: all .15s ease;

}


.btn-export:hover {

    background: #bb2d3b;

    color: #ffffff !important;

    transform: translateY(-1px);

}


/* =========================================================
   AREA SCROLL CARD
========================================================= */

.schedule-card-body {

    height: calc(100% - 60px);

    padding: 12px;

    overflow: auto;

}


/* =========================================================
   SCROLLBAR
========================================================= */

.schedule-card-body::-webkit-scrollbar {

    width: 9px;

    height: 9px;

}


.schedule-card-body::-webkit-scrollbar-track {

    background: #f1f1f1;

    border-radius: 10px;

}


.schedule-card-body::-webkit-scrollbar-thumb {

    background: #b8b8b8;

    border-radius: 10px;

}


.schedule-card-body::-webkit-scrollbar-thumb:hover {

    background: #888;

}


/* =========================================================
   TABLE
========================================================= */

.schedule-table {

    width: 100%;

    min-width: 900px;

    border-collapse: collapse;

    table-layout: fixed;

}


/* =========================================================
   HEADER TABLE
========================================================= */

.schedule-table thead th {

    background: #212529;

    color: #ffffff;

    border: 1px solid #4b4f52;

    text-align: center;

    vertical-align: middle;

    font-size: 14px;

    font-weight: 700;

    padding: 8px 5px;

}


/* =========================================================
   LEBAR KOLOM
========================================================= */

.schedule-table thead th:nth-child(1) {

    width: 105px;

}


.schedule-table thead th:nth-child(2) {

    width: 110px;

}


/* =========================================================
   TABLE BODY
========================================================= */

.schedule-table tbody td {

    border: 1px solid #d8dde2;

    text-align: center;

    vertical-align: middle;

    height: 55px;

    font-size: 13px;

    padding: 5px;

}


/* =========================================================
   HARI
========================================================= */

.day-cell {

    font-size: 14px !important;

    font-weight: 700;

    background: #ffffff;

}


/* =========================================================
   WAKTU
========================================================= */

.time-cell {

    font-size: 13px !important;

    line-height: 1.4;

    background: #ffffff;

}


/* =========================================================
   JADWAL TERISI
========================================================= */

.schedule-filled {

    color: #ffffff;

    font-weight: 500;

    padding: 6px 5px !important;

}


/* =========================================================
   MATA KULIAH
========================================================= */

.subject-name {

    font-size: 14px;

    font-weight: 700;

    margin-bottom: 2px;

    line-height: 1.2;

}


/* =========================================================
   DOSEN
========================================================= */

.lecturer-name {

    font-size: 12px;

    margin-bottom: 2px;

    line-height: 1.2;

}


/* =========================================================
   KELAS
========================================================= */

.class-name {

    font-size: 12px;

    line-height: 1.2;

}


/* =========================================================
   SEL KOSONG
========================================================= */

.empty-cell {

    background: #ffffff;

    color: #222222;

    font-size: 13px;

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {


    .logo {

        font-size: 21px;

    }


    .navbar-buttons {

        gap: 5px;

    }


    .navbar-buttons .btn {

        padding: 6px 9px;

        font-size: 13px;

    }


    .schedule-card {

        width: calc(100% - 20px);

        height: 600px;

        margin-top: 15px;

    }


    .schedule-card-header {

        height: 55px;

        padding: 11px;

    }


    .page-title {

        font-size: 18px;

    }


    .btn-export {

        font-size: 12px;

        padding: 6px 9px;

    }


    .schedule-card-body {

        height: calc(100% - 55px);

        padding: 8px;

    }


    .schedule-table {

        min-width: 800px;

    }


    .schedule-table thead th {

        font-size: 13px;

        padding: 7px 4px;

    }


    .schedule-table tbody td {

        font-size: 12px;

        height: 50px;

    }


    .subject-name {

        font-size: 13px;

    }


    .lecturer-name {

        font-size: 11px;

    }


    .class-name {

        font-size: 11px;

    }

}

</style>

</head>


<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="navbar navbar-custom">


    <div class="container-fluid px-3">


        <!-- LOGO -->

        <a
            class="logo"
            href="./"
        >

            <i class="bi bi-mortarboard-fill"></i>

            SIKULIAH

        </a>



        <!-- LOGIN REGISTER -->

        <div class="navbar-buttons">


            <a
                href="auth/login.php"
                class="btn btn-outline-primary"
            >

                <i class="bi bi-box-arrow-in-right"></i>

                Login

            </a>


            <a
                href="auth/register.php"
                class="btn btn-primary"
            >

                <i class="bi bi-person-plus"></i>

                Register

            </a>


        </div>


    </div>

</nav>



<!-- =========================================================
     CARD JADWAL
========================================================= -->

<div class="schedule-card">


    <!-- HEADER -->

    <div class="schedule-card-header">


        <h1 class="page-title">

            Jadwal Perkuliahan

        </h1>


        <a
            href="export_jadwal.php"
            class="btn-export"
            target="_blank"
        >

            Export PDF

        </a>


    </div>



    <!-- =====================================================
         AREA YANG BISA DI-SCROLL
    ====================================================== -->

    <div class="schedule-card-body">


        <table class="schedule-table">


            <!-- HEADER TABEL -->

            <thead>

            <tr>


                <th>

                    Hari

                </th>


                <th>

                    Waktu

                </th>


                <?php foreach ($ruangan as $r): ?>

                    <th>

                        <?= htmlspecialchars(
                            $r['nama_ruangan']
                        ); ?>

                    </th>

                <?php endforeach; ?>


            </tr>

            </thead>



            <!-- ISI TABEL -->

            <tbody>


            <?php if (
                count($hari) > 0 &&
                count($jam) > 0
            ): ?>


                <?php foreach ($hari as $h): ?>


                    <?php

                    $hariId =
                        $h['id_hari'];

                    $jumlahJam =
                        count($jam);

                    $jamKe = 0;

                    ?>


                    <?php foreach ($jam as $j): ?>


                        <?php

                        $jamId =
                            $j['id_jam'];

                        $jamKe++;

                        ?>


                        <tr>


                            <!-- HARI -->

                            <?php if ($jamKe == 1): ?>

                                <td
                                    class="day-cell"
                                    rowspan="<?= $jumlahJam; ?>"
                                >

                                    <?= htmlspecialchars(
                                        $h['nama_hari']
                                    ); ?>

                                </td>

                            <?php endif; ?>



                            <!-- WAKTU -->

                            <td class="time-cell">

                                <?= substr(
                                    $j['jam_mulai'],
                                    0,
                                    5
                                ); ?>

                                -

                                <br>

                                <?= substr(
                                    $j['jam_selesai'],
                                    0,
                                    5
                                ); ?>

                            </td>



                            <!-- RUANGAN -->

                            <?php foreach ($ruangan as $r): ?>


                                <?php

                                $ruanganId =
                                    $r['id_ruangan'];

                                $data = null;


                                if (
                                    isset(
                                        $jadwalMap
                                            [$hariId]
                                            [$jamId]
                                            [$ruanganId]
                                    )
                                ) {

                                    $data =
                                        $jadwalMap
                                            [$hariId]
                                            [$jamId]
                                            [$ruanganId];

                                }

                                ?>


                                <?php if ($data): ?>


                                    <?php

                                    $warnaCell =
                                        $warnaMK[
                                            $data['id_mk']
                                        ]
                                        ?? '#0d6efd';

                                    ?>


                                    <td
                                        class="schedule-filled"
                                        style="
                                            background:
                                            <?= $warnaCell ?>;
                                        "
                                    >


                                        <div class="subject-name">

                                            <?= htmlspecialchars(
                                                $data['nama_mk']
                                            ); ?>

                                        </div>


                                        <div class="lecturer-name">

                                            <?= htmlspecialchars(
                                                $data['nama_dosen']
                                            ); ?>

                                        </div>


                                        <div class="class-name">

                                            <?= htmlspecialchars(
                                                $data['nama_kelas']
                                            ); ?>

                                        </div>


                                    </td>


                                <?php else: ?>


                                    <td class="empty-cell">

                                        -

                                    </td>


                                <?php endif; ?>


                            <?php endforeach; ?>


                        </tr>


                    <?php endforeach; ?>


                <?php endforeach; ?>


            <?php else: ?>


                <tr>

                    <td
                        colspan="<?= count($ruangan) + 2; ?>"
                        class="text-center"
                    >

                        Belum ada data jadwal.

                    </td>

                </tr>


            <?php endif; ?>


            </tbody>


        </table>


    </div>


</div>


</body>

</html>
