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
CEK ROOT
==================================================
*/

$folder_dosen = [
    'jadwal_dosen'
];

$is_root = !in_array($current_folder, $folder_dosen);


/*
==================================================
URL
==================================================
*/

if ($is_root) {

    $dashboard_url = "dashboard_dosen.php";
    $jadwal_url    = "jadwal_dosen/index.php";
    $logout_url    = "auth/logout.php";

} else {

    $dashboard_url = "../dashboard_dosen.php";
    $jadwal_url    = "../jadwal_dosen/index.php";
    $logout_url    = "../auth/logout.php";

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
   JUDUL
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

.main-content {

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

    .main-content {

        margin-left: 220px;

    }

}

</style>


<!-- =========================================
     SIDEBAR DOSEN
========================================= -->

<div class="sidebar-dosen">


    <!-- HEADER -->

    <div class="sidebar-dosen-header">

        <h4>
            👨‍🏫 Dosen
        </h4>

    </div>


    <!-- MENU -->

    <div class="sidebar-dosen-menu">


        <!-- DASHBOARD -->

        <a
            href="<?= $dashboard_url; ?>"
            class="<?= ($current_page == 'dashboard_dosen.php') ? 'active' : ''; ?>"
        >

            <i class="bi bi-speedometer2"></i>

            <span>
                Dashboard
            </span>

        </a>


        <!-- JADWAL -->

        <div class="sidebar-dosen-title">

            Perkuliahan

        </div>


        <a
            href="<?= $jadwal_url; ?>"
            class="<?= ($current_folder == 'jadwal_dosen') ? 'active' : ''; ?>"
        >

            <i class="bi bi-calendar-week"></i>

            <span>
                Jadwal Saya
            </span>

        </a>


    </div>


    <!-- LOGOUT -->

    <div class="sidebar-dosen-footer">

        <a
            href="<?= $logout_url; ?>"
            onclick="return confirm('Yakin ingin logout?')"
        >

            <i class="bi bi-box-arrow-right"></i>

            <span>
                Logout
            </span>

        </a>

    </div>


</div>


<!-- MAIN CONTENT -->

<div class="main-content">
