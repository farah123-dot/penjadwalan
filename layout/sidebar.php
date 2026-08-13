<div class="sidebar">

    <!-- LOGO -->

    <div class="text-center py-4 border-bottom">

        <h3 class="mb-1">
            <i class="bi bi-calendar3"></i>
            SIKULIAH
        </h3>

        <small class="text-light">
            Sistem Penjadwalan Kuliah
        </small>

    </div>


    <!-- MENU -->

    <div class="mt-3">

        <!-- DASHBOARD -->

        <a href="/">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>


        <hr>


        <!-- MASTER DATA -->

        <small class="text-white-50 px-3">
            MASTER DATA
        </small>


        <a href="/dosen/">
            <i class="bi bi-person-fill"></i>
            Data Dosen
        </a>


        <a href="/mata_kuliah/">
            <i class="bi bi-book-fill"></i>
            Mata Kuliah
        </a>


        <a href="/kelas/">
            <i class="bi bi-people-fill"></i>
            Kelas
        </a>


        <a href="/ruangan/">
            <i class="bi bi-building"></i>
            Ruangan
        </a>


        <a href="/jam/">
            <i class="bi bi-clock-history"></i>
            Jam Kuliah
        </a>


        <a href="/dosen_mk/">
            <i class="bi bi-person-workspace"></i>
            Dosen Mengajar
        </a>


        <hr>


        <!-- PENJADWALAN -->

        <small class="text-white-50 px-3">
            PENJADWALAN
        </small>


        <a href="/jadwal/">
            <i class="bi bi-calendar-week-fill"></i>
            Jadwal Kuliah
        </a>

    </div>


    <!-- USER -->

    <div class="sidebar-user">

        <div class="dropdown">

            <button
                class="btn sidebar-user-btn dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >

                <i class="bi bi-person-circle fs-5"></i>

                <span>

                    <?= $_SESSION['nama']; ?>

                    <small>
                        <?= ucfirst($_SESSION['role']); ?>
                    </small>

                </span>

            </button>


            <!-- DROPDOWN -->

            <ul class="dropdown-menu dropdown-menu-dark shadow">


                <!-- INFORMASI USER -->

                <li>

                    <span class="dropdown-item-text">

                        <i class="bi bi-person-circle"></i>

                        <strong>
                            <?= $_SESSION['nama']; ?>
                        </strong>

                        <br>

                        <small class="text-white-50 ms-4">
                            <?= ucfirst($_SESSION['role']); ?>
                        </small>

                    </span>

                </li>


                <li>
                    <hr class="dropdown-divider">
                </li>


                <!-- LOGOUT -->

                <li>

                    <a
                        href="/logout.php"
                        class="dropdown-item text-danger"
                    >

                        <i class="bi bi-box-arrow-right"></i>

                        Logout

                    </a>

                </li>


            </ul>

        </div>

    </div>

</div>


<!-- CONTENT -->

<div class="content">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded mb-4">

        <div class="container-fluid">

            <h4 class="m-0 fw-bold">

                <i class="bi bi-mortarboard-fill text-primary"></i>

                Sistem Penjadwalan Kuliah

            </h4>

        </div>

    </nav>
