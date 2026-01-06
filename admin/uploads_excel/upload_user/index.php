<?php
// Include koneksi database dan autentikasi
require_once '../../../koneksi.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Data User - SiPagu Admin</title>
    
    <!-- Include CSS Files dari Stisla Template -->
    <link rel="stylesheet" href="../../../assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/modules/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../assets/css/components.css">
    
    <style>
        .upload-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .upload-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e9ecef;
            z-index: 1;
        }
        .step {
            position: relative;
            z-index: 2;
            background: white;
            padding: 0 15px;
            text-align: center;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            font-size: 18px;
            border: 2px solid #e9ecef;
        }
        .step.active .step-circle {
            background: #4361ee;
            color: white;
            border-color: #4361ee;
        }
        .step.completed .step-circle {
            background: #38b000;
            color: white;
            border-color: #38b000;
        }
        .step-title {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }
        .step.active .step-title {
            color: #4361ee;
            font-weight: 600;
        }
        .file-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #4361ee;
            padding: 20px;
            border-radius: 8px;
        }
        .template-link {
            color: #4361ee;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .template-link:hover {
            color: #3a0ca3;
            text-decoration: underline;
        }
        .btn-upload {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border: none;
            padding: 10px 30px;
            font-weight: 600;
            border-radius: 8px;
            color: white;
            transition: all 0.3s;
        }
        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
            color: white;
        }
        .progress-container {
            display: none;
        }
        .upload-stats {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .stat-item {
            text-align: center;
            padding: 10px;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #4361ee;
        }
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            
            <!-- Include Sidebar -->
            <?php include '../../includes/sidebar_admin.php'; ?>
            
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>Upload Data User</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item"><a href="../../index.php">Dashboard</a></div>
                            <div class="breadcrumb-item"><a href="#">Uploads Excel</a></div>
                            <div class="breadcrumb-item active">Upload Data User</div>
                        </div>
                    </div>

                    <div class="section-body">
                        <!-- Upload Stats -->
                        <div class="row mb-4">
                            <div class="col-md-3 col-sm-6">
                                <div class="upload-stats">
                                    <div class="stat-item">
                                        <div class="stat-number">
                                            <?php
                                            $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_user");
                                            $row = mysqli_fetch_assoc($query);
                                            echo $row['total'];
                                            ?>
                                        </div>
                                        <div class="stat-label">Total User</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="upload-stats">
                                    <div class="stat-item">
                                        <div class="stat-number">
                                            <?php
                                            $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_user WHERE role_user = 'admin'");
                                            $row = mysqli_fetch_assoc($query);
                                            echo $row['total'];
                                            ?>
                                        </div>
                                        <div class="stat-label">Admin</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="upload-stats">
                                    <div class="stat-item">
                                        <div class="stat-number">
                                            <?php
                                            $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_user WHERE role_user = 'staff'");
                                            $row = mysqli_fetch_assoc($query);
                                            echo $row['total'];
                                            ?>
                                        </div>
                                        <div class="stat-label">Staff</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="upload-stats">
                                    <div class="stat-item">
                                        <div class="stat-number"><?php echo date('d/m/Y'); ?></div>
                                        <div class="stat-label">Tanggal</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card upload-card">
                                    <div class="card-header">
                                        <h4>Upload File Excel Data User</h4>
                                        <p class="text-muted mb-0">Upload data dosen dan staff universitas</p>
                                    </div>
                                    <div class="card-body">
                                        <!-- Step Indicator -->
                                        <div class="step-indicator">
                                            <div class="step active">
                                                <div class="step-circle">1</div>
                                                <div class="step-title">Pilih File</div>
                                            </div>
                                            <div class="step">
                                                <div class="step-circle">2</div>
                                                <div class="step-title">Validasi</div>
                                            </div>
                                            <div class="step">
                                                <div class="step-circle">3</div>
                                                <div class="step-title">Proses</div>
                                            </div>
                                            <div class="step">
                                                <div class="step-circle">4</div>
                                                <div class="step-title">Selesai</div>
                                            </div>
                                        </div>

                                        <!-- Upload Form -->
                                        <form action="process.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="mb-4">
                                                        <label for="filexls" class="form-label font-weight-bold">Pilih File Excel</label>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="filexls" name="filexls" accept=".xls,.xlsx" required>
                                                            <label class="custom-file-label" for="filexls" id="fileLabel">Choose file...</label>
                                                        </div>
                                                        <div class="form-text text-muted">
                                                            File harus berformat .xls atau .xlsx. Maksimal 10MB.
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Progress Bar -->
                                                    <div class="progress-container mb-3" id="progressContainer">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span>Progress Upload</span>
                                                            <span id="progressPercent">0%</span>
                                                        </div>
                                                        <div class="progress" style="height: 10px;">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                                 id="progressBar" role="progressbar" style="width: 0%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="file-info">
                                                        <h5><i class="fas fa-info-circle text-primary mr-2"></i>Format File Excel</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-borderless">
                                                                <thead>
                                                                    <tr class="text-primary">
                                                                        <th>Kolom</th>
                                                                        <th>Keterangan</th>
                                                                        <th>Contoh</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><strong>A</strong></td>
                                                                        <td>No. Urut</td>
                                                                        <td>1, 2, 3...</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>B</strong></td>
                                                                        <td>NPP User <span class="text-danger">*</span></td>
                                                                        <td>12345678</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>C</strong></td>
                                                                        <td>NIK User</td>
                                                                        <td>3273010101010001</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>D</strong></td>
                                                                        <td>NPWP User</td>
                                                                        <td>01.234.567.8-912.345</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>E</strong></td>
                                                                        <td>No. Rekening</td>
                                                                        <td>1234567890</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>F</strong></td>
                                                                        <td>Nama User</td>
                                                                        <td>John Doe</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>G</strong></td>
                                                                        <td>No. HP</td>
                                                                        <td>081234567890</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="alert alert-warning mt-3">
                                                            <small>
                                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                                <strong>Catatan:</strong> NPP User wajib diisi dan bersifat unik. Data duplikat akan dilewati.
                                                            </small>
                                                        </div>
                                                        <div class="mt-3">
                                                            <a href="#" class="template-link">
                                                                <i class="fas fa-download"></i> Download Template Excel
                                                            </a>
                                                            <a href="#" class="template-link ml-3">
                                                                <i class="fas fa-question-circle"></i> Panduan Upload
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Status Messages -->
                                            <div id="messageContainer" class="mt-3">
                                                <?php
                                                // Tampilkan pesan dari session jika ada
                                                if (isset($_SESSION['upload_message'])) {
                                                    echo $_SESSION['upload_message'];
                                                    unset($_SESSION['upload_message']);
                                                }
                                                ?>
                                            </div>

                                            <!-- Buttons -->
                                            <div class="mt-4 d-flex justify-content-between">
                                                <a href="../../index.php" class="btn btn-outline-secondary btn-icon icon-left">
                                                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                                                </a>
                                                <div>
                                                    <button type="button" class="btn btn-outline-primary btn-icon icon-left mr-2" id="validateBtn">
                                                        <i class="fas fa-check-circle"></i> Validasi File
                                                    </button>
                                                    <button type="submit" name="submit" class="btn btn-upload btn-icon icon-left">
                                                        <i class="fas fa-upload"></i> Upload & Proses
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-shield-alt mr-1"></i>
                                                Sistem akan otomatis membuat password dari NPP (MD5 hashed)
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-user mr-1"></i>
                                                Login sebagai: <?php echo $_SESSION['username'] ?? 'Administrator'; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Uploads -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Riwayat Upload Terbaru</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Tanggal Upload</th>
                                                        <th>Nama File</th>
                                                        <th>Jumlah Data</th>
                                                        <th>Status</th>
                                                        <th>Diupload Oleh</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted py-4">
                                                            <i class="fas fa-database fa-3x mb-3 d-block"></i>
                                                            Belum ada riwayat upload
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Include Footer Scripts -->
            <?php include '../../../includes/footer_scripts.php'; ?>
        </div>
    </div>

    <!-- Custom JavaScript -->
    <script>
        // Update file label
        document.getElementById('filexls').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileLabel = document.getElementById('fileLabel');
            const messageContainer = document.getElementById('messageContainer');
            
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / (1024*1024)).toFixed(2); // MB
                const fileExt = fileName.split('.').pop().toLowerCase();
                
                fileLabel.textContent = fileName + ' (' + fileSize + ' MB)';
                
                // Validasi format file
                if (!['xls', 'xlsx'].includes(fileExt)) {
                    messageContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>×</span>
                                </button>
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Format file tidak valid. Harap pilih file Excel (.xls atau .xlsx)
                            </div>
                        </div>
                    `;
                    e.target.value = '';
                    fileLabel.textContent = 'Choose file...';
                } else if (fileSize > 10) {
                    messageContainer.innerHTML = `
                        <div class="alert alert-warning alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>×</span>
                                </button>
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                File terlalu besar (${fileSize} MB). Maksimal 10 MB
                            </div>
                        </div>
                    `;
                    e.target.value = '';
                    fileLabel.textContent = 'Choose file...';
                } else {
                    messageContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>×</span>
                                </button>
                                <i class="fas fa-check-circle mr-2"></i>
                                File <strong>${fileName}</strong> (${fileSize} MB) siap diupload
                            </div>
                        </div>
                    `;
                }
            }
        });
        
        // Validate button
        document.getElementById('validateBtn').addEventListener('click', function() {
            const fileInput = document.getElementById('filexls');
            const messageContainer = document.getElementById('messageContainer');
            
            if (fileInput.files.length === 0) {
                messageContainer.innerHTML = `
                    <div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                                <span>×</span>
                            </button>
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Silakan pilih file Excel terlebih dahulu
                        </div>
                    </div>
                `;
                return;
            }
            
            // Show validating message
            messageContainer.innerHTML = `
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm mr-2" role="status"></div>
                        <div>Sedang memvalidasi file...</div>
                    </div>
                </div>
            `;
            
            // Simulate validation
            setTimeout(() => {
                messageContainer.innerHTML = `
                    <div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                                <span>×</span>
                            </button>
                            <i class="fas fa-check-circle mr-2"></i>
                            File valid dan siap diupload. Format sesuai dengan template.
                        </div>
                    </div>
                `;
            }, 2000);
        });
        
        // Form submission with progress
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('filexls');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            
            if (fileInput.files.length > 0) {
                progressContainer.style.display = 'block';
                progressBar.style.width = '30%';
                progressPercent.textContent = '30%';
                
                // Simulate progress
                const interval = setInterval(() => {
                    const currentWidth = parseInt(progressBar.style.width);
                    if (currentWidth < 90) {
                        const newWidth = currentWidth + 10;
                        progressBar.style.width = newWidth + '%';
                        progressPercent.textContent = newWidth + '%';
                    }
                }, 500);
                
                // Clear interval after form submit
                setTimeout(() => {
                    clearInterval(interval);
                }, 3000);
            }
        });
        
        // Initialize Bootstrap components
        $(document).ready(function() {
            // Initialize custom file input
            bsCustomFileInput.init();
            
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>