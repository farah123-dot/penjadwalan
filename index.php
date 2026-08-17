```php
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
   AMBIL SEMUA JAM / SESI
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

    $hariId = $row['id_hari'];

    $jamId = $row['id_jam'];

    $ruanganId = $row['id_ruangan'];


    $jadwalMap[$hariId][$jamId][$ruanganId] = $row;

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

    $idMk = $row['id_mk'];


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


<style>

/* =========================================================
   RESET
========================================================= */

* {
    box-sizing: border-box;
}


html {
    scroll-behavior: smooth;
}


body {

    margin: 0;

    background: #f1f5f9;

    color: #111827;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

}


/* =========================================================
   NAVBAR
========================================================= */

.navbar-custom {

    width: 100%;

    min-height: 64px;

    background: #ffffff;

    border-bottom: 1px solid #e5e7eb;

    box-shadow:
        0 2px 10px rgba(0,0,0,.05);

    display: flex;

    align-items: center;

}


.navbar-inner {

    width: 100%;

    padding: 0 25px;

    display: flex;

    align-items: center;

    justify-content: space-between;

}


/* =========================================================
   LOGO
========================================================= */

.logo {

    color: #0d6efd;

    text-decoration: none;

    font-size: 27px;

    font-weight: 700;

    letter-spacing: .2px;

}


.logo-icon {

    display: inline-flex;

    width: 34px;

    height: 34px;

    margin-right: 7px;

    align-items: center;

    justify-content: center;

    background: #0d6efd;

    color: #ffffff;

    border-radius: 8px;

    font-size: 18px;

    vertical-align: middle;

}


/* =========================================================
   NAVBAR BUTTON
========================================================= */

.navbar-buttons {

    display: flex;

    align-items: center;

    gap: 10px;

}


.nav-button {

    min-width: 92px;

    height: 40px;

    padding: 0 17px;

    border-radius: 8px;

    text-decoration: none;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    font-size: 14px;

    font-weight: 600;

    transition: all .15s ease;

}


.login-button {

    background: #ffffff;

    color: #0d6efd;

    border: 1px solid #0d6efd;

}


.login-button:hover {

    background: #0d6efd;

    color: #ffffff;

    transform: translateY(-1px);

}


.register-button {

    background: #0d6efd;

    color: #ffffff;

    border: 1px solid #0d6efd;

    box-shadow:
        0 3px 0 #0a58ca;

}


.register-button:hover {

    background: #0b5ed7;

    color: #ffffff;

    transform: translateY(-1px);

    box-shadow:
        0 4px 0 #0a58ca;

}


.register-button:active {

    transform: translateY(2px);

    box-shadow:
        0 1px 0 #0a58ca;

}


/* =========================================================
   MAIN
========================================================= */

.main-container {

    width: calc(100% - 60px);

    max-width: 1250px;

    margin: 0 auto;

}


/* =========================================================
   INTRO
========================================================= */

.intro {

    padding: 30px 0 10px;

}


.intro-title {

    margin: 0;

    font-size: 28px;

    font-weight: 700;

    color: #111827;

}


.intro-text {

    margin: 8px 0 0;

    color: #64748b;

    font-size: 15px;

}


/* =========================================================
   SCHEDULE CARD
========================================================= */

.schedule-card {

    width: 100%;

    height: 650px;

    margin: 15px 0 35px;

    background: #ffffff;

    border-radius: 15px;

    border: 1px solid #e5e7eb;

    box-shadow:
        0 10px 30px rgba(0,0,0,.08);

    overflow: hidden;

}


/* =========================================================
   CARD HEADER
========================================================= */

.schedule-card-header {

    min-height: 60px;

    padding: 12px 17px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    background: #ffffff;

    border-bottom: 1px solid #e5e7eb;

}


.page-title {

    margin: 0;

    font-size: 20px;

    font-weight: 600;

    color: #111827;

}


/* =========================================================
   EXPORT BUTTON
========================================================= */

.btn-export {

    background: #dc3545;

    color: #ffffff;

    text-decoration: none;

    border: none;

    border-radius: 8px;

    padding: 8px 13px;

    font-size: 14px;

    font-weight: 600;

    transition: all .15s ease;

}


.btn-export:hover {

    background: #bb2d3b;

    color: #ffffff;

    transform: translateY(-1px);

}


/* =========================================================
   TABLE AREA
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

    background: #888888;

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


.schedule-table thead th {

    background: #212529;

    color: #ffffff;

    border: 1px solid #4b4f52;

    text-align: center;

    vertical-align: middle;

    font-size: 14px;

    font-weight: 700;

    padding: 9px 5px;

}


.schedule-table thead th:nth-child(1) {

    width: 105px;

}


.schedule-table thead th:nth-child(2) {

    width: 110px;

}


.schedule-table tbody td {

    border: 1px solid #d8dde2;

    text-align: center;

    vertical-align: middle;

    height: 55px;

    font-size: 13px;

    padding: 5px;

}


.schedule-table tbody tr:hover td {

    background: #f8fafc;

}


.day-cell {

    font-size: 14px !important;

    font-weight: 700;

    background: #ffffff;

}


.time-cell {

    font-size: 13px !important;

    line-height: 1.4;

    background: #ffffff;

}


.schedule-filled {

    color: #ffffff;

    font-weight: 500;

    padding: 6px 5px !important;

}


.subject-name {

    font-size: 14px;

    font-weight: 700;

    margin-bottom: 2px;

    line-height: 1.2;

}


.lecturer-name {

    font-size: 12px;

    margin-bottom: 2px;

    line-height: 1.2;

}


.class-name {

    font-size: 12px;

    line-height: 1.2;

}


.empty-cell {

    background: #ffffff;

    color: #222222;

    font-size: 13px;

}


/* =========================================================
   FOOTER
========================================================= */

.footer {

    text-align: center;

    padding: 0 20px 25px;

    color: #64748b;

    font-size: 13px;

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    .navbar-inner {

        padding: 0 15px;

    }


    .logo {

        font-size: 21px;

    }


    .logo-icon {

        width: 29px;

        height: 29px;

        font-size: 15px;

    }


    .navbar-buttons {

        gap: 5px;

    }


    .nav-button {

        min-width: auto;

        height: 36px;

        padding: 0 10px;

        font-size: 12px;

    }


    .main-container {

        width: calc(100% - 20px);

    }


    .intro {

        padding-top: 20px;

    }


    .intro-title {

        font-size: 23px;

    }


    .intro-text {

        font-size: 14px;

    }


    .schedule-card {

        height: 600px;

        margin-top: 12px;

    }


    .schedule-card-header {

        min-height: 55px;

        padding: 10px;

    }


    .page-title {

        font-size: 18px;

    }


    .btn-export {

        font-size: 12px;

        padding: 7px 9px;

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

<header class="navbar-custom">

    <div class="navbar-inner">


        <a
            href="./"
            class="logo"
        >

            <span class="logo-icon">S</span>

            SIKULIAH

        </a>


        <div class="navbar-buttons">


            <a
                href="auth/login.php"
                class="nav-button login-button"
            >

                Login

            </a>


            <a
                href="auth/register.php"
                class="nav-button register-button"
            >

                Register

            </a>


        </div>


    </div>

</header>



<!-- =========================================================
     MAIN CONTENT
========================================================= -->

<main class="main-container">


    <section class="intro">

        <h1 class="intro-title">

            Sistem Penjadwalan Kuliah

        </h1>


        <p class="intro-text">

            Informasi jadwal perkuliahan yang dapat dilihat oleh mahasiswa dan dosen.

        </p>

    </section>



    <!-- =====================================================
         JADWAL
    ====================================================== -->

    <section class="schedule-card">


        <div class="schedule-card-header">


            <h2 class="page-title">

                Jadwal Perkuliahan

            </h2>


            <a
                href="export_jadwal.php"
                class="btn-export"
                target="_blank"
            >

                Export PDF

            </a>


        </div>



        <div class="schedule-card-body">


            <table class="schedule-table">


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
                                                background-color:
                                                <?= htmlspecialchars(
                                                    $warnaCell
                                                ); ?>;
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
                            class="empty-cell"
                        >

                            Belum ada data jadwal.

                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>


            </table>


        </div>


    </section>


</main>



<footer class="footer">

    SIKULIAH &copy; <?= date('Y'); ?> —
    Sistem Penjadwalan Kuliah

</footer>


</body>

</html>
```
