<?php

require_once "koneksi.php";
require_once "auth/cek_login.php";

include "layout/header.php";
include "layout/sidebar.php";


/* ===============================
   TIMEZONE INDONESIA
================================ */

date_default_timezone_set('Asia/Jakarta');


/* ===============================
   FUNGSI HITUNG DATA
================================ */

function jumlahData($conn, $tabel)
{
    $query = mysqli_query($conn, "SELECT * FROM $tabel");

    if ($query) {
        return mysqli_num_rows($query);
    }

    return 0;
}


/* ===============================
   JUMLAH DATA
================================ */

$jumlah_dosen   = jumlahData($conn, "dosen");
$jumlah_mk      = jumlahData($conn, "mata_kuliah");
$jumlah_kelas   = jumlahData($conn, "kelas");
$jumlah_ruangan = jumlahData($conn, "ruangan");
$jumlah_jam     = jumlahData($conn, "jam_kuliah");
$jumlah_jadwal  = jumlahData($conn, "jadwal");


/* ===============================
   AMBIL DATA JADWAL
================================ */

$query_jadwal = mysqli_query($conn, "

SELECT 
    jadwal.id_jadwal,
    kelas.nama_kelas,
    hari.nama_hari,
    jam_kuliah.jam_mulai,
    jam_kuliah.jam_selesai,
    ruangan.nama_ruangan,
    dosen.nama_dosen,
    mata_kuliah.nama_mk

FROM jadwal

JOIN hari
ON jadwal.id_hari = hari.id_hari

JOIN jam_kuliah
ON jadwal.id_jam = jam_kuliah.id_jam

JOIN ruangan
ON jadwal.id_ruangan = ruangan.id_ruangan

JOIN dosen_mk
ON jadwal.id_dosen_mk = dosen_mk.id

JOIN dosen
ON dosen_mk.id_dosen = dosen.id_dosen

JOIN mata_kuliah
ON dosen_mk.id_mk = mata_kuliah.id_mk

JOIN kelas
ON dosen_mk.id_kelas = kelas.id_kelas

ORDER BY 
hari.id_hari ASC,
jam_kuliah.jam_mulai ASC

");


/* ===============================
   HARI SEKARANG
================================ */

$hari_inggris = date('l');

$daftar_hari = [
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu',
    'Sunday'    => 'Minggu'
];

$hari_sekarang = $daftar_hari[$hari_inggris];

/* ===============================
   WARNA MATA KULIAH
================================ */

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

/* ===============================
   DASHBOARD
================================ */

.dashboard-container {
    padding: 25px;
    width: 100%;
    box-sizing: border-box;
}


/* ===============================
   JUDUL
================================ */

.dashboard-title {
    margin-bottom: 25px;
}

.dashboard-title h2 {
    margin: 0;
    font-size: 26px;
    font-weight: 700;
    color: #1e293b;
}

.dashboard-title p {
    margin-top: 6px;
    color: #64748b;
    font-size: 14px;
}


/* ===============================
   CARD
================================ */

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 18px;
    margin-bottom: 30px;
}

.dashboard-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.07);
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: 0.2s ease;
}

.dashboard-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 7px 20px rgba(0,0,0,0.10);
}


/* ===============================
   ICON
================================ */

.dashboard-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 23px;
    flex-shrink: 0;
}

.icon-dosen {
    background: #dbeafe;
    color: #2563eb;
}

.icon-mk {
    background: #dcfce7;
    color: #16a34a;
}

.icon-kelas {
    background: #fef3c7;
    color: #d97706;
}

.icon-ruangan {
    background: #ede9fe;
    color: #7c3aed;
}

.icon-jadwal {
    background: #fee2e2;
    color: #dc2626;
}


/* ===============================
   CARD CONTENT
================================ */

.dashboard-content {
    min-width: 0;
}

.dashboard-label {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 4px;
}

.dashboard-number {
    font-size: 25px;
    font-weight: 700;
    color: #1e293b;
}

/* ===============================
   JADWAL LENGKAP
================================ */

.schedule-box {
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.07);
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.schedule-header {
    padding: 18px 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.schedule-header h3 {
    margin: 0;
    font-size: 18px;
    color: #1e293b;
}

.export-button {
    background: #dc2626;
    color: white;
    text-decoration: none;
    padding: 9px 15px;
    border-radius: 7px;
    font-size: 13px;
    transition: 0.2s;
}

.export-button:hover {
    background: #b91c1c;
    color: white;
}


/* ===============================
   TABLE JADWAL
================================ */

.schedule-body {
    padding: 20px;
    overflow-x: auto;
}

.schedule-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
    font-size: 13px;
}

.schedule-table th {
    background: #1e293b;
    color: white;
    padding: 12px 10px;
    border: 1px solid #334155;
    white-space: nowrap;
}

.schedule-table td {
    border: 1px solid #e5e7eb;
    padding: 10px;
    text-align: center;
    vertical-align: middle;
}

.schedule-table .hari {
    background: #f8fafc;
    font-weight: 700;
    color: #334155;
}

.schedule-table .jam {
    background: #f8fafc;
    white-space: nowrap;
    font-weight: 500;
}

.jadwal-cell {
    color: white;
    border-radius: 4px;
}

.jadwal-cell b {
    font-size: 12px;
}

.jadwal-cell small {
    font-size: 11px;
}

.cell-kosong {
    color: #94a3b8;
}


/* ===============================
   RESPONSIVE
================================ */

@media (max-width: 1200px) {

    .dashboard-grid {
        grid-template-columns: repeat(3, 1fr);
    }

}


@media (max-width: 768px) {

    .dashboard-container {
        padding: 15px;
    }

    .dashboard-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .schedule-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

}


@media (max-width: 500px) {

    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-title h2 {
        font-size: 22px;
    }

}

</style>


<div class="dashboard-container">

    <!-- ===============================
         CARD STATISTIK
    ================================ -->

    <div class="dashboard-grid">


        <!-- DOSEN -->

        <div class="dashboard-card">

            <div class="dashboard-icon icon-dosen">
                👨‍🏫
            </div>

            <div class="dashboard-content">

                <div class="dashboard-label">
                    Data Dosen
                </div>

                <div class="dashboard-number">
                    <?= $jumlah_dosen ?>
                </div>

            </div>

        </div>


        <!-- MATA KULIAH -->

        <div class="dashboard-card">

            <div class="dashboard-icon icon-mk">
                📚
            </div>

            <div class="dashboard-content">

                <div class="dashboard-label">
                    Mata Kuliah
                </div>

                <div class="dashboard-number">
                    <?= $jumlah_mk ?>
                </div>

            </div>

        </div>


        <!-- KELAS -->

        <div class="dashboard-card">

            <div class="dashboard-icon icon-kelas">
                🏫
            </div>

            <div class="dashboard-content">

                <div class="dashboard-label">
                    Data Kelas
                </div>

                <div class="dashboard-number">
                    <?= $jumlah_kelas ?>
                </div>

            </div>

        </div>


        <!-- RUANGAN -->

        <div class="dashboard-card">

            <div class="dashboard-icon icon-ruangan">
                🏢
            </div>

            <div class="dashboard-content">

                <div class="dashboard-label">
                    Data Ruangan
                </div>

                <div class="dashboard-number">
                    <?= $jumlah_ruangan ?>
                </div>

            </div>

        </div>


        <!-- JADWAL -->

        <div class="dashboard-card">

            <div class="dashboard-icon icon-jadwal">
                📅
            </div>

            <div class="dashboard-content">

                <div class="dashboard-label">
                    Total Jadwal
                </div>

                <div class="dashboard-number">
                    <?= $jumlah_jadwal ?>
                </div>

            </div>

        </div>


    </div>

    <!-- ===============================
         TABEL JADWAL LENGKAP
    ================================ -->

    <div class="schedule-box">


        <div class="schedule-header">

            <h3>
                Jadwal Perkuliahan
            </h3>


            <a href="export_jadwal.php"
               class="export-button">

                Export PDF

            </a>

        </div>


        <div class="schedule-body">


            <table class="schedule-table">


                <thead>

                    <tr>


                        <th width="120">
                            Hari
                        </th>


                        <th width="120">
                            Waktu
                        </th>


                        <?php

                        $data_ruangan = mysqli_query($conn, "

                            SELECT *

                            FROM ruangan

                            ORDER BY id_ruangan ASC

                        ");


                        while ($ruangan = mysqli_fetch_assoc($data_ruangan)) {

                        ?>


                            <th>

                                <?= htmlspecialchars($ruangan['nama_ruangan']) ?>

                            </th>


                        <?php

                        }

                        ?>


                    </tr>

                </thead>


                <tbody>


                    <?php

                    $data_hari = mysqli_query($conn, "

                        SELECT *

                        FROM hari

                        ORDER BY id_hari ASC

                    ");


                    while ($hari = mysqli_fetch_assoc($data_hari)) {


                        $data_jam = mysqli_query($conn, "

                            SELECT *

                            FROM jam_kuliah

                            ORDER BY id_jam ASC

                        ");


                        $jumlah_jam_hari = mysqli_num_rows($data_jam);

                        $first = true;


                        while ($jam = mysqli_fetch_assoc($data_jam)) {

                    ?>


                            <tr>


                                <?php if ($first) { ?>


                                    <td

                                        rowspan="<?= $jumlah_jam_hari ?>"

                                        class="hari"

                                    >

                                        <?= htmlspecialchars($hari['nama_hari']) ?>

                                    </td>


                                <?php

                                    $first = false;

                                }

                                ?>


                                <td class="jam">

                                    <?= htmlspecialchars($jam['jam_mulai']) ?>

                                    -

                                    <?= htmlspecialchars($jam['jam_selesai']) ?>

                                </td>


                                <?php


                                $data_ruangan2 = mysqli_query($conn, "

                                    SELECT *

                                    FROM ruangan

                                    ORDER BY id_ruangan ASC

                                ");


                                while ($ruang = mysqli_fetch_assoc($data_ruangan2)) {


                                    $id_hari = $hari['id_hari'];

                                    $id_jam = $jam['id_jam'];

                                    $id_ruangan = $ruang['id_ruangan'];


                                    $jadwal = mysqli_query($conn, "

                                        SELECT

                                            mata_kuliah.nama_mk,

                                            dosen.nama_dosen,

                                            kelas.nama_kelas

                                        FROM jadwal

                                        JOIN dosen_mk

                                        ON jadwal.id_dosen_mk = dosen_mk.id

                                        JOIN mata_kuliah

                                        ON dosen_mk.id_mk = mata_kuliah.id_mk

                                        JOIN dosen

                                        ON dosen_mk.id_dosen = dosen.id_dosen

                                        JOIN kelas

                                        ON dosen_mk.id_kelas = kelas.id_kelas

                                        WHERE jadwal.id_hari = '$id_hari'

                                        AND jadwal.id_jam = '$id_jam'

                                        AND jadwal.id_ruangan = '$id_ruangan'

                                    ");


                                    if (mysqli_num_rows($jadwal) > 0) {


                                        $data = mysqli_fetch_assoc($jadwal);

                                        $nama_mk = $data['nama_mk'];


                                        if (!isset($warna_mk[$nama_mk])) {

                                            $warna_mk[$nama_mk] =

                                                $daftar_warna[

                                                    $index_warna %

                                                    count($daftar_warna)

                                                ];

                                            $index_warna++;

                                        }


                                        $warna = $warna_mk[$nama_mk];

                                ?>


                                        <td

                                            class="jadwal-cell"

                                            style="background-color: <?= $warna ?>;"

                                        >


                                            <b>

                                                <?= htmlspecialchars($data['nama_mk']) ?>

                                            </b>


                                            <br>


                                            <small>

                                                <?= htmlspecialchars($data['nama_dosen']) ?>

                                            </small>


                                            <br>


                                            <small>

                                                <?= htmlspecialchars($data['nama_kelas']) ?>

                                            </small>


                                        </td>


                                <?php

                                    } else {

                                ?>


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
