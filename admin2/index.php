<?php
/**
 * DASHBOARD ADMIN - SiPagu
 * Lokasi: admin/index.php
 * PATH YANG BENAR: includes/ (karena satu folder dengan includes)
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/function_helper.php';
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/sidebar_admin.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="mb-1">Dashboard</h1>
                <p class="subtitle">Sistem Pengelolaan Keuangan Universitas</p>
            </div>
            <div class="header-actions">
                <div class="date-display">
                    <div class="date-day"><?php echo date('l'); ?></div>
                    <div class="date-full"><?php echo date('d F Y'); ?></div>
                </div>
                <button class="btn-notification">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
            </div>
        </div>
    </div>

    <div class="content-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-card">
                <div class="welcome-content">
                    <div class="welcome-text">
                        <h2>Selamat Datang, <span class="highlight"><?php echo htmlspecialchars($_SESSION['nama'] ?? 'Admin'); ?></span></h2>
                        <p class="welcome-description">Sistem dalam kondisi optimal. Terakhir login: <?php echo date('H:i'); ?></p>
                        
                        <div class="system-status-badges">
                            <div class="status-badge active">
                                <i class="fas fa-circle"></i>
                                <span>Sistem Aktif</span>
                            </div>
                            <div class="status-badge uptime">
                                <i class="fas fa-server"></i>
                                <span>Uptime 99.8%</span>
                            </div>
                        </div>
                    </div>
                    <div class="welcome-graphic">
                        <div class="graphic-circle">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon user">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>5</span>
                    </div>
                </div>
                <div class="stat-content">
                    <div class="stat-value">
                        <?php
                        $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_user");
                        $row = mysqli_fetch_assoc($query);
                        echo number_format($row['total']);
                        ?>
                    </div>
                    <div class="stat-label">Total User</div>
                    <div class="stat-subtitle">+5 bulan ini</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon transaction">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="stat-trend neutral">
                        <i class="fas fa-minus"></i>
                        <span>0</span>
                    </div>
                </div>
                <div class="stat-content">
                    <div class="stat-value">
                        <?php
                        $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_transaksi_ujian");
                        $row = mysqli_fetch_assoc($query);
                        echo number_format($row['total']);
                        ?>
                    </div>
                    <div class="stat-label">Transaksi Ujian</div>
                    <div class="stat-subtitle">Semester 20241</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon committee">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>3</span>
                    </div>
                </div>
                <div class="stat-content">
                    <div class="stat-value">
                        <?php
                        $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_panitia");
                        $row = mysqli_fetch_assoc($query);
                        echo number_format($row['total']);
                        ?>
                    </div>
                    <div class="stat-label">Panitia PA/TA</div>
                    <div class="stat-subtitle">Bimbingan aktif</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon semester">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-trend neutral">
                        <i class="fas fa-minus"></i>
                        <span>0</span>
                    </div>
                </div>
                <div class="stat-content">
                    <div class="stat-value">20241</div>
                    <div class="stat-label">Semester Aktif</div>
                    <div class="stat-subtitle">Berjalan normal</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="section">
            <div class="section-header">
                <div class="section-title">
                    <h3>Quick Actions</h3>
                    <p class="section-subtitle">Akses cepat ke fitur upload data</p>
                </div>
                <div class="section-actions">
                    <button class="btn-icon" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-history"></i>
                            Riwayat Upload
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cog"></i>
                            Pengaturan
                        </a>
                    </div>
                </div>
            </div>

            <div class="actions-grid">
                <a href="uploads_excel/upload_user/" class="action-item">
                    <div class="action-icon user">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Upload User</div>
                        <div class="action-description">Data dosen/staff</div>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>

                <a href="uploads_excel/upload_tu/" class="action-item">
                    <div class="action-icon transaction">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Transaksi Ujian</div>
                        <div class="action-description">Data honor ujian</div>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>

                <a href="uploads_excel/upload_tpata/" class="action-item">
                    <div class="action-icon committee">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Panitia PA/TA</div>
                        <div class="action-description">Data bimbingan</div>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>

                <a href="uploads_excel/upload_panitia/" class="action-item">
                    <div class="action-icon data">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Data Panitia</div>
                        <div class="action-description">Jabatan & honor</div>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>

                <a href="uploads_excel/upload_thd/" class="action-item">
                    <div class="action-icon schedule">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Jadwal Kuliah</div>
                        <div class="action-description">Jadwal perkuliahan</div>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>

                <a href="uploads_excel/upload_jadwal/" class="action-item">
                    <div class="action-icon other">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Jadwal Lain</div>
                        <div class="action-description">Data tambahan</div>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>

        <!-- Activity & Status Section -->
        <div class="grid-section">
            <!-- Recent Activity -->
            <div class="activity-section">
                <div class="section-header">
                    <div class="section-title">
                        <h3>Aktivitas Terbaru</h3>
                        <p class="section-subtitle">Update terbaru dari sistem</p>
                    </div>
                    <a href="#" class="btn-link">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-marker success"></div>
                        <div class="activity-content">
                            <div class="activity-header">
                                <div class="activity-title">Upload Data User</div>
                                <div class="activity-time">2 jam lalu</div>
                            </div>
                            <div class="activity-description">25 data dosen/staff baru berhasil diimport</div>
                            <span class="activity-badge success">Selesai</span>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-marker primary"></div>
                        <div class="activity-content">
                            <div class="activity-header">
                                <div class="activity-title">Update Transaksi Ujian</div>
                                <div class="activity-time">1 hari lalu</div>
                            </div>
                            <div class="activity-description">Transaksi semester 20241 telah diperbarui</div>
                            <span class="activity-badge primary">Berhasil</span>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-marker info"></div>
                        <div class="activity-content">
                            <div class="activity-header">
                                <div class="activity-title">Import Jadwal</div>
                                <div class="activity-time">3 hari lalu</div>
                            </div>
                            <div class="activity-description">File jadwal_20241.xlsx berhasil diproses</div>
                            <span class="activity-badge info">Diproses</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="status-section">
                <div class="section-header">
                    <div class="section-title">
                        <h3>Status Sistem</h3>
                        <p class="section-subtitle">Monitor performa sistem</p>
                    </div>
                </div>

                <div class="status-list">
                    <div class="status-item">
                        <div class="status-header">
                            <div class="status-title">
                                <i class="fas fa-database"></i>
                                <span>Database</span>
                            </div>
                            <span class="status-indicator success"></span>
                        </div>
                        <div class="status-progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <div class="status-info">Response: 12ms</div>
                    </div>

                    <div class="status-item">
                        <div class="status-header">
                            <div class="status-title">
                                <i class="fas fa-hdd"></i>
                                <span>Storage</span>
                            </div>
                            <span class="status-value">65%</span>
                        </div>
                        <div class="status-progress">
                            <div class="progress-bar warning" style="width: 65%"></div>
                        </div>
                        <div class="status-info">325GB / 500GB</div>
                    </div>

                    <div class="status-item">
                        <div class="status-header">
                            <div class="status-title">
                                <i class="fas fa-server"></i>
                                <span>Uptime</span>
                            </div>
                            <span class="status-value success">99.8%</span>
                        </div>
                        <div class="status-progress">
                            <div class="progress-bar info" style="width: 99.8%"></div>
                        </div>
                        <div class="status-info">30 hari terakhir</div>
                    </div>

                    <div class="status-item">
                        <div class="status-header">
                            <div class="status-title">
                                <i class="fas fa-users"></i>
                                <span>Users Online</span>
                            </div>
                            <span class="status-value">12</span>
                        </div>
                        <div class="status-progress">
                            <div class="progress-bar" style="width: 60%"></div>
                        </div>
                        <div class="status-info">Dari 20 user aktif</div>
                    </div>
                </div>

                <div class="status-actions">
                    <button class="btn-secondary">
                        <i class="fas fa-sync-alt"></i>
                        Refresh
                    </button>
                    <button class="btn-secondary">
                        <i class="fas fa-chart-bar"></i>
                        Detail
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
<?php include __DIR__ . '/includes/footer_scripts.php'; ?>