<?php

/*
==================================================
SIDEBAR ADMIN
==================================================
*/

$current_page = basename($_SERVER['PHP_SELF']);
$current_folder = basename(dirname($_SERVER['PHP_SELF']));

?>

<style>

/* =========================================
   SIDEBAR ADMIN
========================================= */

.sidebar-admin {

    width: 250px;
    min-height: 100vh;

    background: #1e293b;
    color: white;

    position: fixed;

    top: 0;
    left: 0;

    z-index: 1000;

    display: flex;
    flex-direction: column;

}


/* =========================================
   HEADER SIDEBAR
========================================= */

.sidebar-admin-header {

    height: 70px;

    display: flex;

    align-items: center;

    padding: 0 20px;

    border-bottom: 1px solid #334155;

}

.sidebar-admin-header h4 {

    margin: 0;

    font-size: 17px;

    font-weight: 700;

    color: white;

}


/* =========================================
   MENU
========================================= */

.sidebar-admin-menu {

    padding: 15px 10px;

    flex: 1;

    overflow-y: auto;

}

.sidebar-admin-menu a {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 11px 14px;

    margin-bottom: 5px;

    color: #cbd5e1;

    text-decoration: none;

    border-radius: 8px;

    font-size: 14px;

    transition: 0.2s ease;

}

.sidebar-admin-menu a:hover {

    background: #334155;

    color: white;

}

.sidebar-admin-menu a.active {

    background: #2563eb;

    color: white;

}


/* =========================================
   ICON
========================================= */

.sidebar-admin-menu i {

    width: 20px;

    text-align: center;

    font-size: 16px;

}


/* =========================================
   JUDUL MENU
========================================= */

.sidebar-admin-title {

    color: #64748b;

    font-size: 11px;

    font-weight: 700;

    padding: 15px 14px 7px;

    text-transform: uppercase;

}


/* =========================================
   FOOTER
========================================= */

.sidebar-admin-footer {

    padding: 10px;

    border-top: 1px solid #334155;

}

.sidebar-admin-footer a {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 11px 14px;

    color: #fca5a5;

    text-decoration: none;

    border-radius: 8px;

    font-size: 14px;

    transition: 0.2s ease;

}

.sidebar-admin-footer a:hover {

    background: #450a0a;

    color: #fecaca;

}


/* =========================================
   MAIN CONTENT
========================================= */

.main-content {

    margin-left: 250px;

    min-height: 100vh;

}


/* =========================================
   RESPONSIVE
========================================= */

@media (max-width: 768px) {

    .sidebar-admin {

        width: 220px;

    }

    .main-content {

        margin-left: 220px;

    }

}

</style>


<!-- =========================================
     SIDEBAR ADMIN
========================================= -->

<div class="sidebar-admin">


    <!-- =====================================
         HEADER
    ====================================== -->

    <div class="sidebar-admin-header">

        <h4>
            📚 Admin
        </h4>

    </div>


    <!-- =====================================
         MENU
    ====================================== -->

    <div class="sidebar-admin-menu">


        <!-- =================================
             DASHBOARD
        ================================== -->

        <a
            href="<?= ($current_folder == 'layout') ? 'dashboard_admin.php' : '../dashboard_admin.php'; ?>"
            class="<?= ($current_page == 'index.php' && $current_folder != 'jadwal') ? 'active' : ''; ?>"
        >

            <i class="bi bi-speedometer2"></i>

            <span>
                Dashboard
            </span>

        </a>


        <!-- =================================
             DATA MASTER
        ================================== -->

        <div class="sidebar-admin-title">

            Data Master

        </div>


        <!-- DATA DOSEN -->

        <a
            href="<?= ($current_folder == 'dosen') ? 'index.php' : '../dosen/index.php'; ?>"
            class="<?= ($current_folder == 'dosen') ? 'active' : ''; ?>"
        >

            <i class="bi bi-person-badge"></i>

            <span>
                Data Dosen
            </span>

        </a>


        <!-- MATA KULIAH -->

        <a
            href="<?= ($current_folder == 'mata_kuliah') ? 'index.php' : '../mata_kuliah/index.php'; ?>"
            class="<?= ($current_folder == 'mata_kuliah') ? 'active' : ''; ?>"
        >

            <i class="bi bi-book"></i>

            <span>
                Mata Kuliah
            </span>

        </a>


        <!-- KELAS -->

        <a
            href="<?= ($current_folder == 'kelas') ? 'index.php' : '../kelas/index.php'; ?>"
            class="<?= ($current_folder == 'kelas') ? 'active' : ''; ?>"
        >

            <i class="bi bi-people"></i>

            <span>
                Kelas
            </span>

        </a>


        <!-- KELAS DIBUKA -->

        <a
            href="<?= ($current_folder == 'kelas_dibuka') ? 'index.php' : '../kelas_dibuka/index.php'; ?>"
            class="<?= ($current_folder == 'kelas_dibuka') ? 'active' : ''; ?>"
        >

            <i class="bi bi-journal-check"></i>

            <span>
                Kelas Dibuka
            </span>

        </a>


        <!-- RUANGAN -->

        <a
            href="<?= ($current_folder == 'ruangan') ? 'index.php' : '../ruangan/index.php'; ?>"
            class="<?= ($current_folder == 'ruangan') ? 'active' : ''; ?>"
        >

            <i class="bi bi-building"></i>

            <span>
                Ruangan
            </span>

        </a>


        <!-- HARI -->

        <a
            href="<?= ($current_folder == 'hari') ? 'index.php' : '../hari/index.php'; ?>"
            class="<?= ($current_folder == 'hari') ? 'active' : ''; ?>"
        >

            <i class="bi bi-calendar"></i>

            <span>
                Hari
            </span>

        </a>


        <!-- SESI / JAM -->

        <a
            href="<?= ($current_folder == 'jam_kuliah') ? 'index.php' : '../jam_kuliah/index.php'; ?>"
            class="<?= ($current_folder == 'jam_kuliah') ? 'active' : ''; ?>"
        >

            <i class="bi bi-clock"></i>

            <span>
                Sesi / Jam Kuliah
            </span>

        </a>


        <!-- =================================
             JADWAL
        ================================== -->

        <div class="sidebar-admin-title">

            Jadwal

        </div>


        <!-- JADWAL KULIAH -->

        <a
            href="<?= ($current_folder == 'jadwal') ? 'index.php' : '../jadwal/index.php'; ?>"
            class="<?= ($current_folder == 'jadwal') ? 'active' : ''; ?>"
        >

            <i class="bi bi-calendar-week"></i>

            <span>
                Jadwal Kuliah
            </span>

        </a>


    </div>


    <!-- =====================================
         LOGOUT
    ====================================== -->

    <div class="sidebar-admin-footer">

        <a
            href="../auth/logout.php"
            onclick="return confirm('Yakin ingin logout?')"
        >

            <i class="bi bi-box-arrow-right"></i>

            <span>
                Logout
            </span>

        </a>

    </div>


</div>


<!-- =========================================
     MAIN CONTENT
========================================= -->

<div class="main-content">
