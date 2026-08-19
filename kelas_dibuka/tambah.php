<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar_admin.php";


/* ===============================
   DATA KELAS
================================ */

$kelas = mysqli_query($conn, "
    SELECT *
    FROM kelas
    ORDER BY nama_kelas ASC
");


/* ===============================
   DATA MATA KULIAH
================================ */

$mata_kuliah = mysqli_query($conn, "
    SELECT *
    FROM mata_kuliah
    ORDER BY semester ASC, nama_mk ASC
");

?>

<style>

.page-container {
    padding: 25px;
}

.form-box {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.07);
    max-width: 700px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-weight: 600;
    color: #334155;
}

.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
}

.btn-simpan {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px 17px;
    border-radius: 7px;
}

.btn-kembali {
    background: #64748b;
    color: white;
    padding: 10px 17px;
    border-radius: 7px;
    text-decoration: none;
}

</style>


<div class="page-container">

    <div class="form-box">

        <h3>➕ Tambah Kelas Dibuka</h3>

        <hr>

        <form action="simpan.php" method="POST">


            <!-- KELAS -->

            <div class="form-group">

                <label>
                    Kelas
                </label>

                <select name="id_kelas" required>

                    <option value="">
                        -- Pilih Kelas --
                    </option>

                    <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>

                        <option value="<?= $k['id_kelas']; ?>">

                            <?= htmlspecialchars($k['nama_kelas']); ?>

                        </option>

                    <?php } ?>

                </select>

            </div>


            <!-- MATA KULIAH -->

            <div class="form-group">

                <label>
                    Mata Kuliah
                </label>

                <select name="id_mk" required>

                    <option value="">
                        -- Pilih Mata Kuliah --
                    </option>

                    <?php while ($mk = mysqli_fetch_assoc($mata_kuliah)) { ?>

                        <option value="<?= $mk['id_mk']; ?>">

                            <?= htmlspecialchars($mk['kode_mk']); ?>
                            -
                            <?= htmlspecialchars($mk['nama_mk']); ?>
                            (Semester <?= htmlspecialchars($mk['semester']); ?>)

                        </option>

                    <?php } ?>

                </select>

            </div>


            <button type="submit" class="btn-simpan">
                💾 Simpan
            </button>

            <a href="index.php" class="btn-kembali">
                Kembali
            </a>

        </form>

    </div>

</div>


<?php
include "../layout/footer.php";
?>
