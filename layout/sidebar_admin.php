<?php

$current_page   = basename($_SERVER['PHP_SELF']);
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


/* HEADER */

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


/* MENU */

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

    transition: 0.2s;
}

.sidebar-admin-menu a:hover {

    background: #334155;

    color: white;
}

.sidebar-admin-menu a.active {

    background: #2563eb;

    color: white;
}


/* ICON */

.sidebar-admin-menu i {

    width: 20px;

    text-align: center;

    font-size: 16px;
}


/* JUDUL */

.sidebar-admin-title {

    color: #64748b;

    font-size: 11px;

    font-weight: 700;

    padding: 15px 14px 7px;

    text-transform: uppercase;
}


/* FOOTER */

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
}

.sidebar-admin-footer a:hover {

    background: #450a0a;

    color: #fecaca;
}


/* MAIN */

.main-content {

    margin-left: 250px;

    min-height: 100vh;
}


/* RESPONSIVE */

@media (max-width: 768px) {

    .sidebar-admin {

        width: 220px;
    }

    .main-content {

        margin-left: 220px;
    }

}

</style>


<div class="sidebar-admin">


    <!-- HEADER -->

    <div class="sidebar-admin-header">

        <h4>
            📚 Admin
        </h4>

    </div>


    <!-- MENU -->

    <div class="sidebar-admin-menu">


        <!-- =================================
             DASHBOARD ADMIN
        ================================== -->

        <a
            href="../dashboard_admin.php"
            class="<?= ($current_page == 'dashboard_admin.php') ? 'active' : ''; ?>"
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


        <!-- DOSEN -->

        <a
            href="../dosen/index.php"
            class="<?= ($current_folder == 'dosen') ? 'active' : ''; ?>"
        >

            <i class="bi bi-person-badge"></i>

            <span>
                Data Dosen
            </span>

        </a>


        <!-- MATA KULIAH -->

        <a
            href="../mata_kuliah/index.php"
            class="<?= ($current_folder == 'mata_kuliah') ? 'active' : ''; ?>"
        >

            <i class="bi bi-book"></i>

            <span>
                Mata Kuliah
            </span>

        </a>


        <!-- KELAS -->

        <a
            href="../kelas/index.php"
            class="<?= ($current_folder == 'kelas') ? 'active' : ''; ?>"
        >

            <i class="bi bi-people"></i>

            <span>
                Data Kelas
            </span>

        </a>


        <!-- KELAS DIBUKA -->

        <a
            href="../kelas_dibuka/index.php"
            class="<?= ($current_folder == 'kelas_dibuka') ? 'active' : ''; ?>"
        >

            <i class="bi bi-journal-check"></i>

            <span>
                Kelas Dibuka
            </span>

        </a>


        <!-- RUANGAN -->

        <a
            href="../ruangan/index.php"
            class="<?= ($current_folder == 'ruangan') ? 'active' : ''; ?>"
        >

            <i class="bi bi-building"></i>

            <span>
                Ruangan
            </span>

        </a>


        <!-- HARI -->

        <a
            href="../hari/index.php"
            class="<?= ($current_folder == 'hari') ? 'active' : ''; ?>"
        >

            <i class="bi bi-calendar"></i>

            <span>
                Hari
            </span>

        </a>


        <!-- JAM -->

        <a
            href="../jam_kuliah/index.php"
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


        <!-- JADWAL -->

        <a
            href="../jadwal/index.php"
            class="<?= ($current_folder == 'jadwal') ? 'active' : ''; ?>"
        >

            <i class="bi bi-calendar-week"></i>

            <span>
                Jadwal Kuliah
            </span>

        </a>


    </div>


    <!-- =================================
         LOGOUT
    ================================== -->

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


<div class="main-content">
