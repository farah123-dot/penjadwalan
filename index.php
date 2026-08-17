<?php

require_once "koneksi.php";


/* ===============================
   AMBIL DATA JADWAL
================================ */

$query = mysqli_query($conn, "

SELECT
    j.id_jadwal,
    h.nama_hari,
    jk.jam_mulai,
    jk.jam_selesai,
    r.nama_ruangan,
    k.nama_kelas,
    d.nama_dosen,
    mk.kode_mk,
    mk.nama_mk,
    mk.semester

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
    h.id_hari,
    jk.jam_mulai,
    k.nama_kelas

");


/* ===============================
   WARNA BERDASARKAN SEMESTER
================================ */

$warna_semester = [

    1 => "#0d6efd", // Biru
    2 => "#198754", // Hijau
    3 => "#ffc107", // Kuning
    4 => "#dc3545", // Merah
    5 => "#6f42c1", // Ungu
    6 => "#20c997", // Cyan
    7 => "#fd7e14", // Orange
    8 => "#d63384"  // Pink

];

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<title>SIKULIAH - Jadwal Kuliah</title>


<style>

/* =========================
   RESET
========================= */

* {
    box-sizing: border-box;
}


body {

    margin: 0;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background: #ffffff;

    color: #212529;

}


/* =========================
   NAVBAR
========================= */

.navbar {

    width: 100%;

    background: #ffffff;

    border-bottom: 1px solid #e5e7eb;

    box-shadow:
        0 3px 15px rgba(0,0,0,.06);

    position: sticky;

    top: 0;

    z-index: 1000;

}


.navbar-container {

    max-width: 1400px;

    margin: auto;

    padding: 15px 25px;

    display: flex;

    align-items: center;

    justify-content: space-between;

}


.logo {

    display: flex;

    align-items: center;

    gap: 10px;

    font-size: 27px;

    font-weight: 700;

    color: #0d6efd;

    text-decoration: none;

}


.logo img {

    width: 42px;

    height: 42px;

    object-fit: contain;

}


.nav-buttons {

    display: flex;

    gap: 10px;

}


/* =========================
   BUTTON
========================= */

.btn {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 100px;

    padding: 10px 18px;

    border-radius: 8px;

    text-decoration: none;

    font-size: 14px;

    font-weight: 600;

    transition: .2s;

}


.btn-login {

    background: #ffffff;

    color: #0d6efd;

    border: 1px solid #0d6efd;

}


.btn-login:hover {

    background: #0d6efd;

    color: #ffffff;

}


.btn-register {

    background: #0d6efd;

    color: #ffffff;

    border: 1px solid #0d6efd;

    box-shadow:
        0 4px 0 #0a58ca,
        0 6px 12px rgba(13,110,253,.20);

}


.btn-register:hover {

    background: #0b5ed7;

    color: #ffffff;

    transform: translateY(-2px);

    box-shadow:
        0 6px 0 #0a58ca,
        0 9px 16px rgba(13,110,253,.25);

}


.btn-register:active {

    transform: translateY(2px);

    box-shadow:
        0 2px 0 #0a58ca;

}


/* =========================
   CONTAINER
========================= */

.container {

    max-width: 1400px;

    margin: auto;

    padding: 0 25px 40px;

}


/* =========================
   HERO
========================= */

.hero {

    background: #0d6efd;

    color: #ffffff;

    border-radius: 18px;

    padding: 45px 30px;

    margin-top: 30px;

    margin-bottom: 30px;

    text-align: center;

}


.hero h1 {

    margin: 0 0 10px;

    font-size: 34px;

}


.hero p {

    margin: 0;

    font-size: 16px;

    opacity: .9;

}


/* =========================
   CARD
========================= */

.card {

    background: #ffffff;

    border-radius: 14px;

    border: 1px solid #e5e7eb;

    box-shadow:
        0 5px 20px rgba(0,0,0,.06);

    overflow: hidden;

}


.card-header {

    padding: 18px 20px;

    border-bottom: 1px solid #e5e7eb;

}


.card-header h2 {

    margin: 0;

    font-size: 21px;

    color: #212529;

}


.card-body {

    padding: 20px;

}


/* =========================
   JADWAL WRAPPER
========================= */

.jadwal-wrapper {

    width: 100%;

    max-height: 650px;

    overflow: auto;

    position: relative;

    border: 1px solid #dee2e6;

    border-radius: 10px;

}


/* =========================
   TABLE
========================= */

.jadwal-table {

    min-width: 1100px;

    width: max-content;

    border-collapse: separate;

    border-spacing: 0;

}


/* =========================
   HEADER ATAS
   HARI + WAKTU + RUANGAN
========================= */

.jadwal-table thead th {

    position: sticky;

    top: 0;

    z-index: 30;

    background: #000000;

    color: #ffffff;

    padding: 13px 15px;

    text-align: center;

    vertical-align: middle;

    white-space: nowrap;

    border: 1px solid #333333;

}


/* =========================
   KOLOM HARI
========================= */

.jadwal-table .kolom-hari {

    position: sticky;

    left: 0;

    z-index: 20;

    min-width: 120px;

    width: 120px;

    background: #ffffff;

    box-shadow:
        2px 0 5px rgba(0,0,0,.10);

}


/* =========================
   KOLOM WAKTU
========================= */

.jadwal-table .kolom-waktu {

    position: sticky;

    left: 120px;

    z-index: 20;

    min-width: 130px;

    width: 130px;

    background: #ffffff;

    box-shadow:
        2px 0 5px rgba(0,0,0,.10);

}


/* =========================
   HEADER HARI & WAKTU
========================= */

.jadwal-table thead .kolom-hari,
.jadwal-table thead .kolom-waktu {

    background: #000000;

    color: #ffffff;

    z-index: 40;

}


/* =========================
   TABLE BODY
========================= */

.jadwal-table td {

    min-width: 160px;

    height: 75px;

    padding: 10px;

    text-align: center;

    vertical-align: middle;

    white-space: nowrap;

    border: 1px solid #dee2e6;

    background: #ffffff;

}


/* =========================
   HARI
========================= */

.jadwal-table tbody .kolom-hari {

    font-weight: 700;

    background: #ffffff;

}


/* =========================
   WAKTU
========================= */

.jadwal-table tbody .kolom-waktu {

    font-weight: 600;

    background: #ffffff;

}


/* =========================
   JADWAL TERISI
========================= */

.jadwal-terisi {

    color: #ffffff;

    min-width: 180px !important;

    font-weight: 500;

    transition: .2s;

}


/* =========================
   HOVER JADWAL
========================= */

.jadwal-terisi:hover {

    filter: brightness(90%);

    transform: scale(1.01);

}


.jadwal-terisi strong {

    font-size: 14px;

}


.jadwal-terisi small {

    font-size: 12px;

}


/* =========================
   JADWAL KOSONG
========================= */

.jadwal-kosong {

    color: #adb5bd;

    background: #ffffff;

}


/* =========================
   HOVER BARIS
========================= */

.jadwal-table tbody tr:hover .kolom-hari,
.jadwal-table tbody tr:hover .kolom-waktu {

    background: #f5f9ff;

}


/* =========================
   SCROLLBAR
========================= */

.jadwal-wrapper::-webkit-scrollbar {

    width: 10px;

    height: 10px;

}


.jadwal-wrapper::-webkit-scrollbar-track {

    background: #f1f1f1;

}


.jadwal-wrapper::-webkit-scrollbar-thumb {

    background: #777777;

    border-radius: 10px;

}


.jadwal-wrapper::-webkit-scrollbar-thumb:hover {

    background: #444444;

}


/* =========================
   LEGEND SEMESTER
========================= */

.legend {

    display: flex;

    flex-wrap: wrap;

    gap: 10px;

    margin-top: 20px;

    padding-top: 15px;

    border-top: 1px solid #eeeeee;

}


.legend-item {

    display: flex;

    align-items: center;

    gap: 6px;

    font-size: 13px;

    color: #555555;

}


.legend-color {

    width: 16px;

    height: 16px;

    border-radius: 4px;

    display: inline-block;

}


/* =========================
   FOOTER
========================= */

.footer {

    text-align: center;

    padding: 25px;

    color: #6c757d;

    font-size: 13px;

}


/* =========================
   MOBILE
========================= */

@media (max-width: 768px) {

    .navbar-container {

        padding: 12px 15px;

    }


    .logo {

        font-size: 22px;

    }


    .logo img {

        width: 35px;

        height: 35px;

    }


    .nav-buttons {

        gap: 5px;

    }


    .btn {

        min-width: auto;

        padding: 8px 12px;

        font-size: 13px;

    }


    .container {

        padding: 0 15px 30px;

    }


    .hero {

        padding: 30px 20px;

    }


    .hero h1 {

        font-size: 25px;

    }


    .jadwal-wrapper {

        max-height: 550px;

    }


    .jadwal-table {

        min-width: 1000px;

    }

}

</style>

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar">

    <div class="navbar-container">


        <a href="index.php" class="logo">

            <img
                src="assets/logo.png"
                alt="Logo SIKULIAH"
            >

            SIKULIAH

        </a>


        <div class="nav-buttons">

            <a
                href="auth/login.php"
                class="btn btn-login"
            >
                Login
            </a>


            <a
                href="auth/register.php"
                class="btn btn-register"
            >
                Register
            </a>

        </div>

    </div>

</nav>



<!-- =========================
     CONTENT
========================= -->

<div class="container">


    <div class="hero">

        <h1>
            Sistem Penjadwalan Kuliah
        </h1>

        <p>
            Informasi jadwal perkuliahan
        </p>

    </div>



    <div class="card">


        <div class="card-header">

            <h2>
                Jadwal Perkuliahan
            </h2>

        </div>


        <div class="card-body">


            <div class="jadwal-wrapper">


                <table class="jadwal-table">


                    <thead>

                        <tr>

                            <th class="kolom-hari">
                                Hari
                            </th>

                            <th class="kolom-waktu">
                                Waktu
                            </th>


                            <?php

                            $data_ruangan = mysqli_query(
                                $conn,
                                "
                                SELECT *
                                FROM ruangan
                                ORDER BY id_ruangan ASC
                                "
                            );


                            while (
                                $ruangan =
                                mysqli_fetch_assoc($data_ruangan)
                            ) {

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

                    $data_hari = mysqli_query(
                        $conn,
                        "
                        SELECT *
                        FROM hari
                        ORDER BY id_hari ASC
                        "
                    );


                    while (
                        $hari =
                        mysqli_fetch_assoc($data_hari)
                    ) {


                        $data_jam = mysqli_query(
                            $conn,
                            "
                            SELECT *
                            FROM jam_kuliah
                            ORDER BY id_jam ASC
                            "
                        );


                        $jumlah_jam =
                            mysqli_num_rows($data_jam);


                        $first = true;


                        while (
                            $jam =
                            mysqli_fetch_assoc($data_jam)
                        ) {

                    ?>

                        <tr>


                            <?php

                            if ($first) {

                            ?>

                                <td
                                    rowspan="<?= $jumlah_jam ?>"
                                    class="kolom-hari"
                                >

                                    <?= htmlspecialchars(
                                        $hari['nama_hari']
                                    ); ?>

                                </td>

                            <?php

                                $first = false;

                            }

                            ?>


                            <td class="kolom-waktu">

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

                            $data_ruangan2 =
                                mysqli_query(
                                    $conn,
                                    "
                                    SELECT *
                                    FROM ruangan
                                    ORDER BY id_ruangan ASC
                                    "
                                );


                            while (
                                $ruang =
                                mysqli_fetch_assoc(
                                    $data_ruangan2
                                )
                            ) {


                                $id_hari =
                                    $hari['id_hari'];

                                $id_jam =
                                    $jam['id_jam'];

                                $id_ruangan =
                                    $ruang['id_ruangan'];


                                $jadwal =
                                    mysqli_query(
                                        $conn,
                                        "

                                        SELECT

                                            mata_kuliah.nama_mk,
                                            mata_kuliah.semester,
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
                                            '$id_hari'

                                        AND

                                            jadwal.id_jam =
                                            '$id_jam'

                                        AND

                                            jadwal.id_ruangan =
                                            '$id_ruangan'

                                        "
                                    );


                                if (
                                    mysqli_num_rows(
                                        $jadwal
                                    ) > 0
                                ) {


                                    $data =
                                        mysqli_fetch_assoc(
                                            $jadwal
                                        );


                                    $semester =
                                        (int)$data['semester'];


                                    if (
                                        isset(
                                            $warna_semester[
                                                $semester
                                            ]
                                        )
                                    ) {

                                        $warna =
                                            $warna_semester[
                                                $semester
                                            ];

                                    } else {

                                        $warna =
                                            "#6c757d";

                                    }

                            ?>

                                    <td
                                        class="jadwal-terisi"
                                        style="
                                            background-color:
                                            <?= $warna ?>;
                                        "
                                    >

                                        <strong>

                                            <?= htmlspecialchars(
                                                $data['nama_mk']
                                            ); ?>

                                        </strong>

                                        <br>

                                        <small>

                                            <?= htmlspecialchars(
                                                $data['nama_dosen']
                                            ); ?>

                                        </small>

                                        <br>

                                        <small>

                                            <?= htmlspecialchars(
                                                $data['nama_kelas']
                                            ); ?>

                                        </small>

                                    </td>

                            <?php

                                } else {

                            ?>

                                    <td
                                        class="jadwal-kosong"
                                    >
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


            <!-- =========================
                 LEGEND SEMESTER
            ========================= -->

            <div class="legend">

                <?php foreach (
                    $warna_semester
                    as $semester => $warna
                ) { ?>

                    <div class="legend-item">

                        <span
                            class="legend-color"
                            style="
                                background:
                                <?= $warna ?>;
                            "
                        ></span>

                        Semester <?= $semester ?>

                    </div>

                <?php } ?>

            </div>


        </div>

    </div>


</div>



<div class="footer">

    SIKULIAH &copy;
    <?= date('Y'); ?>

</div>


</body>

</html>
