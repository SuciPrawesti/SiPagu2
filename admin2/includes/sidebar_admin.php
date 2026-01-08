<?php
/**
 * SIDEBAR TEMPLATE - SiPagu
 * Lokasi: admin/includes/sidebar_admin.php
 * HANYA sidebar HTML saja, BUKAN full page
 */
?>
<!-- Sidebar -->
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= BASE_URL ?>admin/index.php">
                <img src="<?= BASE_URL ?>/assets/img/logoSiPagu.png" alt="Logo" style="max-height: 40px; max-width: 150px; object-fit: contain;">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= BASE_URL ?>admin/index.php">
                <img src="<?= BASE_URL ?>/assets/img/logoSiPagu.png" alt="Logo" style="max-height: 30px; max-width: 40px; object-fit: contain;">
            </a>
        </div>
        
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="menu-header">Dashboard</li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'admin/uploads_excel') === false ? 'active' : ''; ?>">
                <a href="<?= BASE_URL ?>admin/index.php" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            
            <!-- Uploads Excel Menu -->
            <li class="menu-header">Uploads Excel</li>
            <?php
            $is_upload_page = strpos($_SERVER['REQUEST_URI'], 'uploads_excel') !== false;
            ?>
            <li class="dropdown <?php echo $is_upload_page ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-upload"></i><span>Upload Data</span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], 'upload_user') !== false ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>/uploads_excel/upload_user/">Upload Data User</a>
                    </li>
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], 'upload_panitia') !== false ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>/uploads_excel/upload_panitia/">Upload Data Panitia</a>
                    </li>
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], 'upload_tu') !== false ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>/uploads_excel/upload_tu/">Upload Transaksi Ujian</a>
                    </li>
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], 'upload_tpata') !== false ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>/uploads_excel/upload_tpata/">Upload Panitia PA/TA</a>
                    </li>
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], 'upload_thd') !== false ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>/uploads_excel/upload_thd/">Upload Jadwal</a>
                    </li>
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], 'upload_jadwal') !== false ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>/uploads_excel/upload_jadwal/">Upload Jadwal Lain</a>
                    </li>
                </ul>
            </li>
            
            <!-- Data Management -->
            <li class="menu-header">Data Management</li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i><span>Master Data</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?= BASE_URL ?>/uploads_excel/upload_user/">Data User</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>/uploads_excel/upload_thd/">Transaksi Honor Dosen</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>/uploads_excel/upload_jadwal/">Jadwal</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>/uploads_excel/upload_panitia/">Panitia</a></li>
                </ul>
            </li>
            
            <!-- Reports -->
            <li class="menu-header">Reports</li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-chart-bar"></i><span>Laporan</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?= BASE_URL ?>/reports/financial.php">Laporan Keuangan</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>/reports/transaction.php">Laporan Transaksi</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>/reports/user.php">Laporan User</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>/reports/schedule.php">Laporan Jadwal</a></li>
                </ul>
            </li>
            
            <!-- Settings -->
            <li class="menu-header">Settings</li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i><span>Pengaturan</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?= BASE_URL ?>/settings/general.php">General Settings</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>/settings/users.php">User Management</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>/settings/database.php">Database</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>/settings/backup.php">Backup & Restore</a></li>
                </ul>
            </li>
        </ul>

        <!-- Documentation Button -->
        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="#" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Documentation
            </a>
        </div>
        
        <!-- Version Info -->
        <div class="text-center p-3 text-muted">
            <small>SiPagu v1.0.0</small><br>
            <small>&copy; <?php echo date('Y'); ?> Universitas</small>
        </div>
    </aside>
</div>
<!-- End Sidebar -->