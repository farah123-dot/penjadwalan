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

$nama_dosen = $_SESSION['nama_dosen']
    ?? $_SESSION['nama']
    ?? $_SESSION['username']
    ?? 'Dosen';


/*
==================================================
JIKA SESSION ID DOSEN ADA
==================================================
*/

if ($id_dosen != '') {

    $data_dosen = mysqli_query($conn, "

        SELECT nama_dosen

        FROM dosen

        WHERE id_dosen = '$id_dosen'

        LIMIT 1

    ");

    if ($data_dosen && mysqli_num_rows($data_dosen) > 0) {

        $d = mysqli_fetch_assoc($data_dosen);

        $nama_dosen = $d['nama_dosen'];

    }

}


/*
==================================================
JUMLAH JADWAL DOSEN
==================================================
*/

$jumlah_jadwal = 0;

if ($id_dosen != '') {

    $query_jumlah = mysqli_query($conn, "

        SELECT COUNT(*) AS total

        FROM jadwal j

        INNER JOIN dosen_mk dm

            ON j.id_dosen_mk = dm.id

        WHERE dm.id_dosen = '$id_dosen'

    ");

    if ($query_jumlah) {

        $hasil = mysqli_fetch_assoc($query_jumlah);

        $jumlah_jadwal = $hasil['total'] ?? 0;

    }

}


/*
==================================================
AMBIL JADWAL DOSEN
==================================================
*/

$query_jadwal = false;

if ($id_dosen != '') {

    $query_jadwal = mysqli_query($conn, "

        SELECT

            j.id_jadwal,

            h.nama_hari,

            jk.jam_mulai,

            jk.jam_selesai,

            r.nama_ruangan,

            k.nama_kelas,

            mk.kode_mk,

            mk.nama_mk

        FROM jadwal j

        INNER JOIN hari h

            ON j.id_hari = h.id_hari

        INNER JOIN jam_kuliah jk

            ON j.id_jam = jk.id_jam

        INNER JOIN ruangan r

            ON j.id_ruangan = r.id_ruangan

        INNER JOIN dosen_mk dm

            ON j.id_dosen_mk = dm.id

        INNER JOIN mata_kuliah mk

            ON dm.id_mk = mk.id_mk

        INNER JOIN kelas k

            ON dm.id_kelas = k.id_kelas

        WHERE dm.id_dosen = '$id_dosen'

        ORDER BY

            h.id_hari ASC,

            jk.jam_mulai ASC

    ");

}


/*
==================================================
WARNA
==================================================
*/

$warna = [

    '#2563eb',
    '#16a34a',
    '#dc2626',
    '#ca8a04',
    '#7c3aed',
    '#0891b2',
    '#ea580c',
    '#9333ea'

];

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
   HEADER
========================================= */

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


/* =========================================
   CARD STATISTIK
========================================= */

.stat-card {

    background: white;

    border: 1px solid #e5e7eb;

    border-radius: 14px;

    padding: 22px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.07);

    margin-bottom: 25px;

    display: flex;

    align-items: center;

    gap: 18px;

    max-width: 350px;

}

.stat-icon {

    width: 55px;

    height: 55px;

    border-radius: 12px;

    background: #dbeafe;

    color: #2563eb;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 25px;

}

.stat-label {

    font-size: 13px;

    color: #64748b;

}

.stat-number {

    font-size: 28px;

    font-weight: 700;

    color: #1e293b;

}


/* =========================================
   JADWAL
========================================= */

.schedule-box {

    background: white;

    border: 1px solid #e5e7eb;

    border-radius: 14px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.07);

    overflow: hidden;

}

.schedule-header {

    padding: 18px 20px;

    border-bottom: 1px solid #e5e7eb;

}

.schedule-header h3 {

    margin: 0;

    font-size: 18px;

    color: #1e293b;

}


/* =========================================
   AREA SCROLL
========================================= */

.schedule-scroll {

    max-height: 550px;

    overflow-x: auto;

    overflow-y: auto;

    padding: 20px;

}


/* =========================================
   TABLE
========================================= */

.schedule-table {

    width: 100%;

    min-width: 850px;

    border-collapse: collapse;

    font-size: 13px;

}

.schedule-table th {

    background: #1e293b;

    color: white;

    padding: 12px;

    border: 1px solid #334155;

    white-space: nowrap;

    position: sticky;

    top: 0;

    z-index: 2;

}

.schedule-table td {

    border: 1px solid #e5e7eb;

    padding: 12px;

    text-align: center;

    vertical-align: middle;

}

.jadwal-card {

    color: white;

    border-radius: 8px;

    padding: 10px;

}

.jadwal-card b {

    font-size: 13px;

}

.jadwal-card small {

    font-size: 11px;

}

.empty {

    text-align: center;

    padding: 30px;

    color: #64748b;

}


/* =========================================
   RESPONSIVE
========================================= */

@media(max-width:768px) {

    .dashboard-dosen {

        padding: 15px;

    }

}

</style>


<div class="dashboard-dosen">


    <!-- =====================================
         HEADER
    ====================================== -->

    <div class="dashboard-header">

        <h2>
            Dashboard Dosen
        </h2>

        <p>
            Selamat datang, <b><?= htmlspecialchars($nama_dosen); ?></b>
        </p>

    </div>


    <!-- =====================================
         CARD JUMLAH JADWAL
    ====================================== -->

    <div class="stat-card">

        <div class="stat-icon">

            📅

        </div>

        <div>

            <div class="stat-label">

                Jadwal Saya

            </div>

            <div class="stat-number">

                <?= $jumlah_jadwal; ?>

            </div>

        </div>

    </div>


    <!-- =====================================
         JADWAL
    ====================================== -->

    <div class="schedule-box">


        <div class="schedule-header">

            <h3>

                Jadwal Perkuliahan Saya

            </h3>

        </div>


        <div class="schedule-scroll">


            <?php if ($query_jadwal && mysqli_num_rows($query_jadwal) > 0) { ?>


                <table class="schedule-table">


                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Hari</th>

                            <th>Waktu</th>

                            <th>Kelas</th>

                            <th>Kode MK</th>

                            <th>Mata Kuliah</th>

                            <th>Ruangan</th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php

                        $no = 1;

                        while ($row = mysqli_fetch_assoc($query_jadwal)) {

                            $warna_sekarang =
                                $warna[$index_warna % count($warna)];

                            $index_warna++;

                        ?>


                            <tr>

                                <td>

                                    <?= $no++; ?>

                                </td>


                                <td>

                                    <b>

                                        <?= htmlspecialchars($row['nama_hari']); ?>

                                    </b>

                                </td>


                                <td>

                                    <?= substr($row['jam_mulai'], 0, 5); ?>

                                    -

                                    <?= substr($row['jam_selesai'], 0, 5); ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars($row['nama_kelas']); ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars($row['kode_mk']); ?>

                                </td>


                                <td>

                                    <div
                                        class="jadwal-card"
                                        style="background: <?= $warna_sekarang ?>;"
                                    >

                                        <b>

                                            <?= htmlspecialchars($row['nama_mk']); ?>

                                        </b>

                                    </div>

                                </td>


                                <td>

                                    <?= htmlspecialchars($row['nama_ruangan']); ?>

                                </td>

                            </tr>


                        <?php } ?>


                    </tbody>


                </table>


            <?php } else { ?>


                <div class="empty">

                    Belum ada jadwal perkuliahan untuk Anda.

                </div>


            <?php } ?>


        </div>

    </div>


</div>


<?php

include "layout/footer.php";

?>
