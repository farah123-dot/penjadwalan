<div class="sidebar">

    <div class="text-center py-4 border-bottom">
        <h3 class="mb-1">
            <i class="bi bi-calendar3"></i>
            SIKULIAH
        </h3>
        <small class="text-light">
            Sistem Penjadwalan Kuliah
        </small>
    </div>

    <div class="mt-3">

        <a href="/penjadwalan-kuliah/">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        <hr>

        <small class="text-white-50 px-3">
            MASTER DATA
        </small>

        <a href="/penjadwalan-kuliah/dosen/">
            <i class="bi bi-person-fill"></i>
            Data Dosen
        </a>

        <a href="/penjadwalan-kuliah/mata_kuliah/">
            <i class="bi bi-book-fill"></i>
            Mata Kuliah
        </a>

        <a href="/penjadwalan-kuliah/kelas/">
            <i class="bi bi-people-fill"></i>
            Kelas
        </a>

        <a href="/penjadwalan-kuliah/ruangan/">
            <i class="bi bi-building"></i>
            Ruangan
        </a>

        <a href="/penjadwalan-kuliah/jam/">
            <i class="bi bi-clock-history"></i>
            Jam Kuliah
        </a>

        <a href="/penjadwalan-kuliah/dosen_mk/">
            <i class="bi bi-person-workspace"></i>
            Dosen Mengajar
        </a>

        <hr>

        <small class="text-white-50 px-3">
            PENJADWALAN
        </small>

        <a href="/penjadwalan-kuliah/jadwal/">
            <i class="bi bi-calendar-week-fill"></i>
            Jadwal Kuliah
        </a>

    </div>

</div>

<div class="content">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded mb-4">

        <div class="container-fluid">

            <h4 class="m-0 fw-bold">
                <i class="bi bi-mortarboard-fill text-primary"></i>
                Sistem Penjadwalan Kuliah
            </h4>

            <div class="ms-auto d-flex align-items-center">

                <span class="me-3 fw-semibold">
                    <i class="bi bi-person-circle"></i>
                    <?= $_SESSION['nama']; ?>
                    (<?= ucfirst($_SESSION['role']); ?>)
                </span>

                <a href="/penjadwalan-kuliah/logout.php"
                   class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </a>

            </div>

        </div>

    </nav>