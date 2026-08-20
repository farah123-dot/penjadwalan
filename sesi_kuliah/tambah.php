<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar_admin.php";

?>

<div class="container-fluid">

    <div class="card shadow">

        <!-- =====================================
             HEADER
        ====================================== -->

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">

                <i class="bi bi-clock"></i>

                Tambah Sesi Kuliah

            </h4>

        </div>


        <!-- =====================================
             BODY
        ====================================== -->

        <div class="card-body">

            <form action="simpan.php" method="POST">


                <!-- =================================
                     JAM MULAI
                ================================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Jam Mulai

                    </label>

                    <input
                        type="time"
                        name="jam_mulai"
                        class="form-control"
                        required
                    >

                </div>


                <!-- =================================
                     JAM SELESAI
                ================================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Jam Selesai

                    </label>

                    <input
                        type="time"
                        name="jam_selesai"
                        class="form-control"
                        required
                    >

                </div>


                <!-- =================================
                     TOMBOL
                ================================== -->

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="bi bi-save"></i>

                    Simpan

                </button>


                <a
                    href="index.php"
                    class="btn btn-secondary"
                >

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
