<!-- Sidebar -->
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        <!-- Brand Logo Full -->
        <div class="sidebar-brand">
            <a href="<?= BASE_URL ?>admin/index.php">
                <img
                    src="<?= ASSETS_URL ?>/img/logoSiPagu.png"
                    alt="Logo SiPagu"
                    style="max-height: 40px; max-width: 150px; object-fit: contain;"
                >
            </a>
        </div>

        <!-- Brand Logo Small (Mini Sidebar) -->
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= BASE_URL ?>admin/index.php">
                <img
                    src="<?= ASSETS_URL ?>/img/logoSiPagu.png"
                    alt="Logo SiPagu"
                    style="max-height: 30px; max-width: 40px; object-fit: contain;"
                >
            </a>
        </div>

        <!-- Menu Items -->
        <ul class="sidebar-menu">
            
            <!-- ================= DASHBOARD ================= -->
            <li class="menu-header">Dashboard</li>
            <li class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">
                <a href="<?= BASE_URL ?>admin/index.php" class="nav-link">
                    <i class="fas fa-fire"></i><span>Dashboard</span>
                </a>
            </li>

            <!-- ================= UPLOADS ================= -->
            <li class="menu-header">Uploads Excel</li>
            <?php 
            $upload_pages = ['upload_user', 'upload_panitia', 'upload_tu', 'upload_tpata', 'upload_thd', 'upload_jadwal'];
            $is_upload_page = in_array(basename($_SERVER['PHP_SELF'], '.php'), $upload_pages);
            ?>
            <li class="dropdown <?= $is_upload_page ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-file-upload"></i><span>Upload Data</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="<?= basename($_SERVER['PHP_SELF']) === 'upload_user.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/upload_user.php">Upload Data User</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) === 'upload_panitia.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/upload_panitia.php">Upload Data Panitia</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) === 'upload_tu.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/upload_tu.php">Upload Transaksi Ujian</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) === 'upload_tpata.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/upload_tpata.php">Upload Panitia PA/TA</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) === 'upload_jadwal.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/upload_jadwal.php">Upload Jadwal</a>
                    </li>
                </ul>
            </li>

            <!-- ================= DATA MANAGEMENT ================= -->
            <li class="menu-header">Data Management</li>
            <!-- ================= MASTER DATA ================= -->
            <li class="menu-header">Master Data</li>
            <li class="dropdown <?= in_array(basename($_SERVER['PHP_SELF']), ['jadwal.php', 'panitia.php', 'honor_dosen.php', 'pa_ta.php', 'transaksi_ujian.php', 'users.php']) ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-database"></i><span>Master Data</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="<?= basename($_SERVER['PHP_SELF']) === 'jadwal.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/jadwal.php">Data Jadwal</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) === 'panitia.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/panitia.php">Data Panitia</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) === 'honor_dosen.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/honor_dosen.php">Data Honor Dosen</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) === 'pa_ta.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/pa_ta.php">Data PA/TA</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) === 'transaksi_ujian.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/transaksi_ujian.php">Data Transaksi Ujian</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/users.php">Data User</a>
                    </li>
                </ul>
            </li>


            <!-- ================= REPORTS ================= -->
            <li class="menu-header">Reports</li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-chart-bar"></i><span>Laporan</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?= BASE_URL ?>admin/reports/financial.php">Laporan Keuangan</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>admin/reports/transaction.php">Laporan Transaksi</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>admin/reports/user.php">Laporan User</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>admin/reports/schedule.php">Laporan Jadwal</a></li>
                </ul>
            </li>

            <!-- ================= SETTINGS ================= -->
            <li class="menu-header">Settings</li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-cog"></i><span>Pengaturan</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?= BASE_URL ?>admin/settings/general.php">General Settings</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>admin/settings/users.php">User Management</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>admin/settings/database.php">Database</a></li>
                    <li><a class="nav-link" href="<?= BASE_URL ?>admin/settings/backup.php">Backup & Restore</a></li>
                </ul>
            </li>

        </ul>

        <!-- ================= FOOTER SIDEBAR ================= -->
        <div class="sidebar-footer mt-4 mb-4 p-3" style="margin-top: auto !important;">
            <!-- Logout Button with Proper Alignment -->
            <form action="<?= BASE_URL ?>logout.php" method="POST" class="w-100">
                <button type="submit" name="logout" class="btn btn-danger btn-lg btn-block btn-icon-split d-flex align-items-center justify-content-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span class="flex-grow-1 text-center">Logout</span>
                </button>
            </form>
        </div>
    </aside>
</div>
<!-- End Sidebar -->

<style>
/* Main sidebar wrapper with flexbox */
#sidebar-wrapper {
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow: hidden;
}

/* Sidebar menu with scroll if needed */
.sidebar-menu {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    min-height: 0;
    max-height: calc(100vh - 180px); /* Reserve space for footer */
    padding-bottom: 10px;
}

/* Custom scrollbar for sidebar menu */
.sidebar-menu::-webkit-scrollbar {
    width: 4px;
}

.sidebar-menu::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.sidebar-menu::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.sidebar-menu::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Footer positioning */
.sidebar-footer {
    flex-shrink: 0;
    position: sticky;
    bottom: 0;
    background: #fff; /* Diubah dari gradient ke solid */
    z-index: 10;
    padding: 12px 15px !important; /* Disederhanakan */
    margin-top: auto;
    border-top: 1px solid #f0f0f0;
}
/* Ensure dropdowns appear above footer */
.dropdown-menu {
    z-index: 1001 !important;
    position: relative;
}

/* Button styles */
.btn-icon-split {
    padding: 10px 15px; /* Diperkecil sedikit */
    border-radius: 6px;
    transition: all 0.3s ease;
    height: 44px; /* Tambahkan tinggi tetap */
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-icon-split:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-icon-split i {
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

/* Adjust for sidebar mini mode */
body.sidebar-mini .sidebar-footer {
    width: 65px;
    padding: 10px !important;
}

body.sidebar-mini .btn-icon-split {
    padding: 8px 10px;
}

body.sidebar-mini .btn-icon-split span {
    display: none;
}

body.sidebar-mini .btn-icon-split i {
    margin-right: 0;
    font-size: 18px;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .sidebar-menu {
        max-height: 60vh;
    }
    
    .sidebar-footer {
        position: relative;
        margin-top: 20px;
        border-top: 1px solid #e0e0e0;
    }
}

/* Fix for dropdown content pushing footer */
.sidebar-menu .dropdown.show .dropdown-menu {
    position: static;
    float: none;
    width: 100%;
    margin-top: 0;
    background-color: #f8f9fa;
    border: none;
    box-shadow: none;
}

.sidebar-menu .dropdown-menu .nav-link {
    padding: 8px 20px !important;
    font-size: 13px;
    color: #495057;
}

.sidebar-menu .dropdown-menu .nav-link:hover,
.sidebar-menu .dropdown-menu .nav-link.active {
    background-color: #e9ecef;
    color: #003d7a;
}
</style>

<script>
// JavaScript untuk mengatur footer dinamis
document.addEventListener('DOMContentLoaded', function() {
    const sidebarMenu = document.querySelector('.sidebar-menu');
    const sidebarFooter = document.querySelector('.sidebar-footer');
    const dropdownToggles = document.querySelectorAll('.nav-link.has-dropdown');
    
    if (!sidebarMenu || !sidebarFooter) return;
    
    // Fungsi untuk menyesuaikan tinggi menu
    function adjustMenuHeight() {
        const windowHeight = window.innerHeight;
        const footerHeight = sidebarFooter.offsetHeight;
        const headerHeight = 120; // Height dari logo dan padding
        
        // Hitung tinggi maksimal untuk menu
        const maxMenuHeight = windowHeight - footerHeight - headerHeight;
        
        // Set tinggi maksimal untuk menu
        sidebarMenu.style.maxHeight = Math.max(maxMenuHeight, 200) + 'px';
        
        // Cek apakah menu membutuhkan scroll
        const menuScrollHeight = sidebarMenu.scrollHeight;
        const menuClientHeight = sidebarMenu.clientHeight;
        
        if (menuScrollHeight > menuClientHeight) {
            sidebarMenu.style.overflowY = 'auto';
        } else {
            sidebarMenu.style.overflowY = 'visible';
        }
    }
    
    // Fungsi untuk mengatur posisi footer saat dropdown dibuka
    function setupDropdownListeners() {
        dropdownToggles.forEach(toggle => {
            // Simpan event listener asli jika ada
            const originalOnClick = toggle.onclick;
            
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Panggil event asli jika ada
                if (typeof originalOnClick === 'function') {
                    originalOnClick.call(this, e);
                }
                
                // Tunggu animasi dropdown selesai, lalu adjust height
                setTimeout(() => {
                    adjustMenuHeight();
                    
                    // Scroll ke dropdown jika perlu
                    const parentLi = this.closest('li');
                    if (parentLi && parentLi.classList.contains('show')) {
                        const dropdownMenu = parentLi.querySelector('.dropdown-menu');
                        if (dropdownMenu && dropdownMenu.style.display === 'block') {
                            // Cek apakah dropdown keluar dari viewport
                            const rect = parentLi.getBoundingClientRect();
                            const footerRect = sidebarFooter.getBoundingClientRect();
                            
                            // Jika dropdown overlap dengan footer, scroll sedikit
                            if (rect.bottom + dropdownMenu.offsetHeight > footerRect.top - 10) {
                                sidebarMenu.scrollTop += dropdownMenu.offsetHeight + 10;
                            }
                        }
                    }
                }, 350); // Sesuaikan dengan durasi animasi dropdown
            });
        });
    }
    
    // Inisialisasi
    adjustMenuHeight();
    setupDropdownListeners();
    
    // Adjust saat window di-resize
    window.addEventListener('resize', adjustMenuHeight);
    
    // Adjust saat sidebar di-toggle (untuk sidebar mini)
    const sidebarToggle = document.querySelector('[data-toggle="sidebar"]');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            setTimeout(adjustMenuHeight, 500); // Tunggu animasi sidebar selesai
        });
    }
    
    // Handle klik di luar dropdown untuk menutup
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            // Tunggu sebentar lalu adjust height
            setTimeout(adjustMenuHeight, 100);
        }
    });
    
    // Mutation observer untuk mendeteksi perubahan pada dropdown
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                // Jika class berubah (dropdown dibuka/ditutup)
                setTimeout(adjustMenuHeight, 100);
            }
        });
    });
    
    // Observasi semua dropdown items
    dropdownToggles.forEach(toggle => {
        const parentLi = toggle.closest('li');
        if (parentLi) {
            observer.observe(parentLi, {
                attributes: true,
                attributeFilter: ['class']
            });
        }
    });
});
</script>