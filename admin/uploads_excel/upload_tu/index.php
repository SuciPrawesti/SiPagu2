<?php
require_once '../../../koneksi.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Transaksi Ujian - SiPagu Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .upload-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .file-info {
            background: #f8f9fa;
            border-left: 4px solid #f72585;
            padding: 15px;
            border-radius: 5px;
        }
        .btn-upload {
            background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);
            border: none;
            padding: 10px 30px;
            font-weight: 600;
        }
        .btn-upload:hover {
            background: linear-gradient(135deg, #b5179e 0%, #f72585 100%);
        }
        .template-link {
            color: #f72585;
            text-decoration: none;
            font-weight: 500;
        }
        .template-link:hover {
            text-decoration: underline;
        }
        .col-highlight {
            background-color: #fff5f7 !important;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <?php include '../../includes/sidebar_admin.php'; ?>
            
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>Upload Transaksi Ujian</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="../../index.php">Dashboard</a></div>
                            <div class="breadcrumb-item">Uploads Excel</div>
                            <div class="breadcrumb-item">Upload Transaksi Ujian</div>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card upload-card">
                                    <div class="card-header">
                                        <h4>Upload File Excel Transaksi Ujian</h4>
                                        <p class="text-muted mb-0">Data transaksi ujian per semester</p>
                                    </div>
                                    <div class="card-body">
                                        <form action="process.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="filexls" class="form-label fw-bold">Pilih File Excel</label>
                                                        <input type="file" class="form-control" id="filexls" name="filexls" accept=".xls,.xlsx" required>
                                                        <div class="form-text">
                                                            Baris pertama adalah header, data mulai dari baris ke-2
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Semester Filter (Optional) -->
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Filter Semester</label>
                                                        <select class="form-select" name="semester_filter">
                                                            <option value="">Semua Semester</option>
                                                            <option value="20231">2023/2024 Ganjil</option>
                                                            <option value="20232">2023/2024 Genap</option>
                                                            <option value="20241">2024/2025 Ganjil</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="file-info">
                                                        <h6><i class="fas fa-info-circle me-2"></i>Format File Excel:</h6>
                                                        <ul class="mb-0">
                                                            <li>Kolom B: Semester</li>
                                                            <li>Kolom C: ID Panitia</li>
                                                            <li>Kolom D: ID User</li>
                                                            <li>Kolom E: Jml Mhs Prodi</li>
                                                            <li>Kolom F: Jml Mhs</li>
                                                            <li>Kolom G: Jml Koreksi</li>
                                                            <li>Kolom H: Jml Matkul</li>
                                                            <li>Kolom I: Jml PGWS Pagi</li>
                                                            <li>Kolom J: Jml PGWS Sore</li>
                                                            <li>Kolom K: Jml Koor Pagi</li>
                                                            <li>Kolom L: Jml Koor Sore</li>
                                                        </ul>
                                                        <div class="mt-3">
                                                            <a href="#" class="template-link">
                                                                <i class="fas fa-download me-1"></i> Download Template
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Status Messages -->
                                            <div id="messageContainer">
                                <?php
                                if (isset($_SESSION['upload_message'])) {
                                    echo $_SESSION['upload_message'];
                                    unset($_SESSION['upload_message']);
                                }
                                ?>
                            </div>

                                            <!-- Buttons -->
                                            <div class="mt-4 d-flex justify-content-between">
                                                <a href="../../index.php" class="btn btn-outline-secondary">
                                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                                </a>
                                                <button type="submit" name="submit" class="btn btn-upload">
                                                    <i class="fas fa-upload me-2"></i>Upload Transaksi Ujian
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <small class="text-muted">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            Data akan dicek duplikat berdasarkan semester, id_panitia, dan id_user
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="row mt-4">
                            <div class="col-lg-3 col-md-6">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-primary">
                                        <i class="fas fa-file-import"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Total Upload</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php
                                            $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi_ujian");
                                            $row = mysqli_fetch_assoc($query);
                                            echo $row['total'];
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-success">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Semester Aktif</h4>
                                        </div>
                                        <div class="card-body">
                                            20241
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-warning">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Data User</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php
                                            $query = mysqli_query($koneksi, "SELECT COUNT(DISTINCT id_user) as total FROM transaksi_ujian");
                                            $row = mysqli_fetch_assoc($query);
                                            echo $row['total'];
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-info">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Update Terakhir</h4>
                                        </div>
                                        <div class="card-body">
                                            <?php echo date('d/m/Y'); ?>
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
    <script>
        // File validation
        document.getElementById('filexls').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const messageContainer = document.getElementById('messageContainer');
            
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / (1024*1024)).toFixed(2); // MB
                const fileExt = fileName.split('.').pop().toLowerCase();
                
                if (!['xls', 'xlsx'].includes(fileExt)) {
                    messageContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Format file tidak valid. Harap pilih file Excel (.xls atau .xlsx)
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    e.target.value = '';
                } else if (fileSize > 10) {
                    messageContainer.innerHTML = `
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            File terlalu besar (${fileSize} MB). Maksimal 10 MB
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    e.target.value = '';
                } else {
                    messageContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            File <strong>${fileName}</strong> (${fileSize} MB) siap diupload
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }
            }
        });
    </script>
</body>
</html>