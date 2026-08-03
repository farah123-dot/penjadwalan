<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar.php";

?>

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-success text-white">

            <h4 class="mb-0">
                <i class="bi bi-person-plus-fill"></i>
                Tambah Data Dosen
            </h4>

        </div>

        <div class="card-body">

            <form action="simpan.php" method="POST">

                <div class="mb-3">

                    <label class="form-label">

                        Nama Dosen

                    </label>

                    <input
                        type="text"
                        name="nama_dosen"
                        class="form-control"
                        placeholder="Masukkan Nama Dosen"
                        required>

                </div>

                <button type="submit" class="btn btn-success">

                    <i class="bi bi-save"></i>

                    Simpan

                </button>

                <a href="index.php" class="btn btn-secondary">

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>

            </form>

        </div>

    </div>

</div>

<?php
include "../layout/footer.php";
?>