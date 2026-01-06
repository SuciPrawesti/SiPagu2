<?php
require_once '../koneksi.php';
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SiPagu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .dashboard-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .upload-quick-link {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            text-decoration: none;
            color: inherit;
            display: block;
            transition: all 0.3s;
        }
        .upload-quick-link:hover {
            transform: translateX(5px);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <?php include 'includes/sidebar_admin.php'; ?>
            
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>Dashboard Admin</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active">Dashboard</div>
                        </div>
                    </div>

                    <div class="section-body">
                        <!-- Welcome Banner -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card dashboard-card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="mb-1">Selamat Datang, Admin!</h4>
                                                <p class="mb-0">Sistem Pengelolaan Keuangan (SiPagu) - Universitas</p>
                                            </div>
                                            <div>
                                                <i class="fas fa-university fa-3x opacity-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="card dashboard-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted">Total User</h6>
                                                <h3>
                                                    <?php
                                                    $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_user");
                                                    $row = mysqli_fetch_assoc($query);
                                                    echo $row['total'];
                                                    ?>
                                                </h3>
                                            </div>
                                            <div class="card-icon bg-primary text-white">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card dashboard-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted">Transaksi Ujian</h6>
                                                <h3>
                                                    <?php
                                                    $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_transaksi_ujian");
                                                    $row = mysqli_fetch_assoc($query);
                                                    echo $row['total'];
                                                    ?>
                                                </h3>
                                            </div>
                                            <div class="card-icon bg-success text-white">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card dashboard-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted">Panitia PA/TA</h6>
                                                <h3>
                                                    <?php
                                                    $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_panitia");
                                                    $row = mysqli_fetch_assoc($query);
                                                    echo $row['total'];
                                                    ?>
                                                </h3>
                                            </div>
                                            <div class="card-icon bg-warning text-white">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card dashboard-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted">Semester Aktif</h6>
                                                <h3>20241</h3>
                                            </div>
                                            <div class="card-icon bg-info text-white">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Upload Links -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Quick Upload Excel</h4>
                                        <p class="text-muted mb-0">Akses cepat ke halaman upload data</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <a href="uploads_excel/upload_user/index.php" class="upload-quick-link bg-light">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <i class="fas fa-users fa-2x text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Upload User</h6>
                                                            <p class="mb-0 text-muted">Data dosen/staff</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="uploads_excel/upload_tu/index.php" class="upload-quick-link bg-light">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <i class="fas fa-file-invoice-dollar fa-2x text-success"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Transaksi Ujian</h6>
                                                            <p class="mb-0 text-muted">Data honor ujian</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="uploads_excel/upload_tpata/index.php" class="upload-quick-link bg-light">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <i class="fas fa-user-tie fa-2x text-warning"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Panitia PA/TA</h6>
                                                            <p class="mb-0 text-muted">Data bimbingan</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <a href="uploads_excel/upload_panitia/index.php" class="upload-quick-link bg-light">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <i class="fas fa-clipboard-list fa-2x text-danger"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Data Panitia</h6>
                                                            <p class="mb-0 text-muted">Jabatan & honor</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="uploads_excel/upload_thd/index.php" class="upload-quick-link bg-light">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <i class="fas fa-calendar-alt fa-2x text-info"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Jadwal</h6>
                                                            <p class="mb-0 text-muted">Jadwal perkuliahan</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="uploads_excel/upload_jadwal/index.php" class="upload-quick-link bg-light">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <i class="fas fa-clock fa-2x text-secondary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Jadwal Lain</h6>
                                                            <p class="mb-0 text-muted">Data tambahan</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Aktivitas Terbaru</h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled list-unstyled-border">
                                            <li class="media">
                                                <div class="media-body">
                                                    <div class="media-title">Upload Data User</div>
                                                    <span class="text-small text-muted">25 data baru</span>
                                                    <div class="text-small text-muted">2 jam yang lalu</div>
                                                </div>
                                            </li>
                                            <li class="media">
                                                <div class="media-body">
                                                    <div class="media-title">Update Transaksi Ujian</div>
                                                    <span class="text-small text-muted">Semester 20241</span>
                                                    <div class="text-small text-muted">1 hari yang lalu</div>
                                                </div>
                                            </li>
                                            <li class="media">
                                                <div class="media-body">
                                                    <div class="media-title">Import Jadwal</div>
                                                    <span class="text-small text-muted">File jadwal_20241.xlsx</span>
                                                    <div class="text-small text-muted">3 hari yang lalu</div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Sistem Status</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Database</span>
                                                <span class="text-success">Normal</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: 100%"></div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Storage</span>
                                                <span>65%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-warning" style="width: 65%"></div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Uptime</span>
                                                <span class="text-success">99.8%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-info" style="width: 99.8%"></div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-4">
                                            <a href="#" class="btn btn-outline-primary">
                                                <i class="fas fa-sync-alt me-2"></i>Refresh Status
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>