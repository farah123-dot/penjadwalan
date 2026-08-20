<?php

/*
==================================================
SIDEBAR DOSEN
==================================================
*/

$current_page   = basename($_SERVER['PHP_SELF']);
$current_folder = basename(dirname($_SERVER['PHP_SELF']));


/*
==================================================
CEK ROOT PROJECT
==================================================
*/

$folder_dosen = [
    'jadwal'
];

$is_root = !in_array($current_folder, $folder_dosen);


/*
==================================================
URL DASHBOARD DOSEN
==================================================
*/

if ($is_root) {

    $dashboard_url = "dashboard_dosen.php";

} else {

    $dashboard_url = "../dashboard_dosen.php";

}


/*
==================================================
URL JADWAL
==================================================
*/

if ($is_root) {

    $jadwal_url = "jadwal/";

} else {

    $jadwal_url = "../jadwal/";

}

?>

<style>

/* =========================================
   SIDEBAR DOSEN
========================================= */

.sidebar-dosen {

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
   HEADER
========================================= */

.sidebar-dosen-header {

    height: 70px;

    display: flex;

    align-items: center;

    padding: 0 20px;

    border-bottom: 1px solid #334155;

}

.sidebar-dosen-header h4 {

    margin: 0;

    font-size: 17px;

    font-weight: 700;

    color: white;

}


/* =========================================
   MENU
========================================= */

.sidebar-dosen-menu {

    padding: 15px 10px;

    flex: 1;

    overflow-y: auto;

}

.sidebar-dosen-menu a {

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

.sidebar-dosen-menu a:hover {

    background: #334155;

    color: white;

}

.sidebar-dosen-menu a.active {

    background: #2563eb;

    color: white;

}


/* =========================================
   ICON
========================================= */

.sidebar-dosen-menu i {

    width: 20px;

    text-align: center;

    font-size: 16px;

}


/* =========================================
   JUDUL MENU
========================================= */

.sidebar-dosen-title {

    color: #64748b;

    font-size: 11px;

    font-weight: 700;

    padding: 15px 14px 7px;

    text-transform: uppercase;

}


/* =========================================
   FOOTER
========================================= */

.sidebar-dosen-footer {

    padding: 10px;

    border-top: 1px solid #334155;

}

.sidebar-dosen-footer a {

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

.sidebar-dosen-footer a:hover {

    background: #450a0a;

    color: #fecaca;

}


/* =========================================
   MAIN CONTENT
========================================= */

.main-content-dosen {

    margin-left: 250px;

    min-height: 100vh;

}


/* =========================================
   RESPONSIVE
========================================= */

@media (max-width: 768px) {

    .sidebar-dosen {

        width: 220px;

    }

    .main-content-dosen {

        margin-left: 220px;

    }

}

</style>


<!-- =========================================
     SIDEBAR DOSEN
========================================= -->

<div class="sidebar-dosen">


    <!-- =====================================
         HEADER
    ====================================== -->

    <div class="sidebar-dosen-header">

        <h4>
            👨‍🏫 Dosen
        </h4>

    </div>


    <!-- =====================================
         MENU
    ====================================== -->

    <div class="sidebar-dosen-menu">


        <!-- =================================
             DASHBOARD
        ================================== -->

        <a
            href="<?= $dashboard_url; ?>"
            class="<?= ($current_page == 'dashboard_dosen.php') ? 'active' : ''; ?>"
        >

            <i class="bi bi-speedometer2"></i>

            <span>
                Dashboard
            </span>

        </a>


        <!-- =================================
             JADWAL
        ================================== -->

        <div class="sidebar-dosen-title">

            Perkuliahan

        </div>


        <a
            href="<?= $jadwal_url; ?>"
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

    <div class="sidebar-dosen-footer">

        <?php if ($is_root) { ?>

            <a
                href="auth/logout.php"
                onclick="return confirm('Yakin ingin logout?')"
            >

        <?php } else { ?>

            <a
                href="../auth/logout.php"
                onclick="return confirm('Yakin ingin logout?')"
            >

        <?php } ?>

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

<div class="main-content-dosen">
