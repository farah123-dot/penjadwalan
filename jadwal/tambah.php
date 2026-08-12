<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar.php";


/*
==================================================
AMBIL DATA DOSEN
==================================================
*/

$dosen = mysqli_query($conn, "
    SELECT *
    FROM dosen
    ORDER BY nama_dosen
");


/*
==================================================
AMBIL KELAS YANG DIBUKA
==================================================
*/

$kelas_dibuka = mysqli_query($conn, "

    SELECT
        kd.id_kelas_dibuka,
        kd.nama_kelas,
        mk.kode_mk,
        mk.nama_mk,
        mk.semester

    FROM kelas_dibuka kd

    JOIN mata_kuliah mk
        ON kd.id_mk = mk.id_mk

    ORDER BY
        mk.semester,
        kd.nama_kelas

");


/*
==================================================
AMBIL HARI
==================================================
*/

$hari = mysqli_query($conn, "

    SELECT *
    FROM hari
    ORDER BY id_hari

");


/*
==================================================
AMBIL SESI / JAM
==================================================
*/

$jam = mysqli_query($conn, "

    SELECT *
    FROM jam_kuliah
    ORDER BY jam_mulai

");

?>

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-success text-white">

            <h4>

                <i class="bi bi-plus-circle"></i>

                Tambah Preferensi Jadwal

            </h4>

        </div>


        <div class="card-body">

            <form action="simpan.php" method="POST">


                <!-- ==============================
                     DOSEN
                =============================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Dosen

                    </label>

                    <select
                        name="id_dosen"
                        class="form-select"
                        required>

                        <option value="">

                            -- Pilih Dosen --

                        </option>

                        <?php while($d=mysqli_fetch_assoc($dosen)){ ?>

                            <option value="<?= $d['id_dosen']; ?>">

                                <?= htmlspecialchars($d['nama_dosen']); ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- ==============================
                     KELAS YANG DIBUKA
                =============================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Kelas / Mata Kuliah

                    </label>

                    <select
                        name="id_kelas_dibuka"
                        class="form-select"
                        required>

                        <option value="">

                            -- Pilih Kelas --

                        </option>

                        <?php while($kd=mysqli_fetch_assoc($kelas_dibuka)){ ?>

                            <option
                                value="<?= $kd['id_kelas_dibuka']; ?>">

                                Semester <?= $kd['semester']; ?>

                                -

                                <?= htmlspecialchars($kd['nama_kelas']); ?>

                                |

                                <?= htmlspecialchars($kd['kode_mk']); ?>

                                -

                                <?= htmlspecialchars($kd['nama_mk']); ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- ==============================
                     HARI
                =============================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Hari

                    </label>

                    <select
                        name="id_hari"
                        class="form-select"
                        required>

                        <option value="">

                            -- Pilih Hari --

                        </option>

                        <?php while($h=mysqli_fetch_assoc($hari)){ ?>

                            <option value="<?= $h['id_hari']; ?>">

                                <?= htmlspecialchars($h['nama_hari']); ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- ==============================
                     SESI
                =============================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Sesi

                    </label>

                    <select
                        name="id_jam"
                        class="form-select"
                        required>

                        <option value="">

                            -- Pilih Sesi --

                        </option>

                        <?php while($j=mysqli_fetch_assoc($jam)){ ?>

                            <option value="<?= $j['id_jam']; ?>">

                                Sesi <?= $j['id_jam']; ?>

                                -

                                <?= substr($j['jam_mulai'],0,5); ?>

                                -

                                <?= substr($j['jam_selesai'],0,5); ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- ==============================
                     TOMBOL
                =============================== -->

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="bi bi-save"></i>

                    Simpan Preferensi

                </button>


                <a
                    href="index.php"
                    class="btn btn-secondary">

                    Kembali

                </a>

            </form>

        </div>

    </div>

</div>


<?php

include "../layout/footer.php";

?>
