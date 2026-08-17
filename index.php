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
   AMBIL DATA HARI
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
   AMBIL DATA JAM / SESI
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
   AMBIL DATA RUANGAN
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
    '#e0a800'

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

<title>SIKULIAH - Sistem Penjadwalan Kuliah</title>


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

    background: #f4f7fb;

    color: #111827;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

}


/* =========================================================
   NAVBAR
========================================================= */

.navbar {

    width: 100%;

    height: 76px;

    background: #ffffff;

    border-bottom: 1px solid #e5e7eb;

    box-shadow:
        0 2px 12px rgba(0,0,0,.06);

    display: flex;

    align-items: center;

}


.navbar-inner {

    width: 100%;

    max-width: 1350px;

    margin: auto;

    padding: 0 25px;

    display: flex;

    align-items: center;

    justify-content: space-between;

}


/* =========================================================
   IDENTITAS LOGO
========================================================= */

.brand {

    display: flex;

    align-items: center;

    gap: 13px;

    text-decoration: none;

}


.brand-logo {

    width: 48px;

    height: 48px;

    object-fit: contain;

}


.brand-text {

    display: flex;

    flex-direction: column;

    line-height: 1.1;

}


.brand-name {

    color: #0d6efd;

    font-size: 25px;

    font-weight: 700;

    letter-spacing: .3px;

}


.brand-subtitle {

    color: #64748b;

    font-size: 11px;

    margin-top: 4px;

}


/* =========================================================
   NAV BUTTON
========================================================= */

.nav-buttons {

    display: flex;

    align-items: center;

    gap: 10px;

}


.nav-btn {

    min-width: 95px;

    height: 40px;

    padding: 0 18px;

    border-radius: 8px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    text-decoration: none;

    font-size: 14px;

    font-weight: 600;

    transition: all .15s ease;

}


.btn-login {

    background: #ffffff;

    color: #0d6efd;

    border: 1px solid #0d6efd;

}


.btn-login:hover {

    background: #0d6efd;

    color: #ffffff;

    transform: translateY(-1px);

}


.btn-register {

    background: #0d6efd;

    color: #ffffff;

    border: 1px solid #0d6efd;

    box-shadow:
        0 3px 0 #0a58ca;

}


.btn-register:hover {

    background: #0b5ed7;

    color: #ffffff;

    transform: translateY(-1px);

    box-shadow:
        0 4px 0 #0a58ca;

}


.btn-register:active {

    transform: translateY(2px);

    box-shadow:
        0 1px 0 #0a58ca;

}


/* =========================================================
   MAIN CONTAINER
========================================================= */

.container {

    width: calc(100% - 60px);

    max-width: 1350px;

    margin: auto;

}


/* =========================================================
   HERO
========================================================= */

.hero {

    margin-top: 30px;

    padding: 38px 30px;

    background: #0d6efd;

    border-radius: 18px;

    color: #ffffff;

    text-align: center;

    box-shadow:
        0 10px 25px rgba(13,110,253,.18);

}


.hero h1 {

    margin: 0;

    font-size: 34px;

    font-weight: 700;

}


.hero p {

    margin: 10px 0 0;

    font-size: 16px;

    opacity: .92;

}


/* =========================================================
   JADWAL CARD
========================================================= */

.schedule-card {

    margin-top: 30px;

    margin-bottom: 35px;

    background: #ffffff;

    border: 1px solid #e5e7eb;

    border-radius: 15px;

    box-shadow:
        0 8px 25px rgba(0,0,0,.07);

    overflow: hidden;

}


/* =========================================================
   CARD HEADER
========================================================= */

.schedule-header {

    min-height: 64px;

    padding: 12px 18px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    border-bottom: 1px solid #e5e7eb;

}


.schedule-title {

    margin: 0;

    font-size: 21px;

    font-weight: 600;

}


.export-btn {

    padding: 9px 15px;

    border-radius: 8px;

    background: #dc3545;

    color: #ffffff;

    text-decoration: none;

    font-size: 13px;

    font-weight: 600;

    transition: .15s;

}


.export-btn:hover {

    background: #bb2d3b;

    color: #ffffff;

    transform: translateY(-1px);

}


/* =========================================================
   TABLE WRAPPER
========================================================= */

.table-wrapper {

    width: 100%;

    overflow-x: auto;

    padding: 15px;

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


.schedule-table th {

    background: #212529;

    color: #ffffff;

    border: 1px solid #4b4f52;

    padding: 11px 6px;

    font-size: 13px;

    text-align: center;

    vertical-align: middle;

}


.schedule-table th:first-child {

    width: 105px;

}


.schedule-table th:nth-child(2) {

    width: 110px;

}


.schedule-table td {

    border: 1px solid #d8dde2;

    padding: 6px;

    height: 58px;

    text-align: center;

    vertical-align: middle;

    font-size: 13px;

}


.schedule-table tbody tr:hover td {

    background: #f8fafc;

}


/* =========================================================
   CELL
========================================================= */

.day-cell {

    background: #ffffff;

    font-weight: 700;

    font-size: 14px !important;

}


.time-cell {

    background: #ffffff;

    line-height: 1.5;

}


.schedule-cell {

    color: #ffffff;

    font-weight: 500;

}


.subject {

    font-size: 13px;

    font-weight: 700;

    line-height: 1.25;

}


.lecturer {

    font-size: 11px;

    margin-top: 3px;

    line-height: 1.25;

}


.class {

    font-size: 11px;

    margin-top: 2px;

}


.empty {

    color: #6b7280;

    background: #ffffff;

}


/* =========================================================
   FOOTER
========================================================= */

.footer {

    padding: 0 20px 25px;

    text-align: center;

    color: #64748b;

    font-size: 13px;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .navbar {

        height: auto;

        min-height: 70px;

    }


    .navbar-inner {

        padding: 10px 15px;

    }


    .brand-logo {

        width: 40px;

        height: 40px;

    }


    .brand-name {

        font-size: 20px;

    }


    .brand-subtitle {

        font-size: 9px;

    }


    .nav-buttons {

        gap: 5px;

    }


    .nav-btn {

        min-width: auto;

        height: 36px;

        padding: 0 10px;

        font-size: 12px;

    }


    .container {

        width: calc(100% - 20px);

    }


    .hero {

        margin-top: 20px;

        padding: 28px 18px;

    }


    .hero h1 {

        font-size: 25px;

    }


    .hero p {

        font-size: 14px;

    }


    .schedule-header {

        padding: 10px;

    }


    .schedule-title {

        font-size: 18px;

    }


    .export-btn {

        padding: 8px 10px;

        font-size: 12px;

    }


    .table-wrapper {

        padding: 10px;

    }

}

</style>

</head>


<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<header class="navbar">

    <div class="navbar-inner">


        <a
            href="index.php"
            class="brand"
        >

            <img
                src="assets/logo_tif.png"
                alt="Logo Teknik Informatika Universitas Trunojoyo Madura"
                class="brand-logo"
            >


            <div class="brand-text">

                <span class="brand-name">
                    SIKULIAH
                </span>

                <span class="brand-subtitle">
                    Sistem Penjadwalan Kuliah
                </span>

            </div>

        </a>


        <div class="nav-buttons">

            <a
                href="auth/login.php"
                class="nav-btn btn-login"
            >
                Login
            </a>


            <a
                href="auth/register.php"
                class="nav-btn btn-register"
            >
                Register
            </a>

        </div>


    </div>

</header>



<!-- =========================================================
     CONTENT
========================================================= -->

<main class="container">


    <!-- HERO -->

    <section class="hero">

        <h1>
            Sistem Penjadwalan Kuliah
        </h1>

        <p>
            Informasi jadwal perkuliahan Teknik Informatika
        </p>

    </section>



    <!-- =====================================================
         JADWAL
    ====================================================== -->

    <section class="schedule-card">


        <div class="schedule-header">

            <h2 class="schedule-title">
                Jadwal Perkuliahan
            </h2>


            <a
                href="export_jadwal.php"
                class="export-btn"
                target="_blank"
            >
                Export PDF
            </a>

        </div>


        <div class="table-wrapper">


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
                                            class="schedule-cell"
                                            style="
                                                background-color:
                                                <?= htmlspecialchars(
                                                    $warnaCell
                                                ); ?>;
                                            "
                                        >

                                            <div class="subject">

                                                <?= htmlspecialchars(
                                                    $data['nama_mk']
                                                ); ?>

                                            </div>


                                            <div class="lecturer">

                                                <?= htmlspecialchars(
                                                    $data['nama_dosen']
                                                ); ?>

                                            </div>


                                            <div class="class">

                                                <?= htmlspecialchars(
                                                    $data['nama_kelas']
                                                ); ?>

                                            </div>

                                        </td>


                                    <?php else: ?>


                                        <td class="empty">

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
                            class="empty"
                        >

                            Belum ada jadwal perkuliahan.

                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>


            </table>


        </div>

    </section>


</main>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="footer">

    SIKULIAH &copy; <?= date('Y'); ?>

    <br>

    Teknik Informatika — Universitas Trunojoyo Madura

</footer>


</body>

</html>
```
