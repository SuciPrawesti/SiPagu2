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
    <title>Upload Jadwal Lain - SiPagu Admin</title>
    
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
            border-left: 4px solid #38b000;
            padding: 15px;
            border-radius: 5px;
        }
        .btn-upload {
            background: linear-gradient(135deg, #38b000 0%, #2d7d46 100%);
            border: none;
            padding: 10px 30px;
            font-weight: 600;
        }
        .btn-upload:hover {
            background: linear-gradient(135deg, #2d7d46 0%, #38b000 100%);
        }
        .template-link {
            color: #38b000;
            text-decoration: none;
            font-weight: 500;
        }
        .template-link:hover {
            text-decoration: underline;
        }
        .upload-stats {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .stat-item {
            text-align: center;
            padding: 15px;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #38b000;
        }
        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
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
                        <h1>Upload Jadwal Lain</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="../../index.php">Dashboard</a></div>
                            <div class="breadcrumb-item">Uploads Excel</div>
                            <div class="breadcrumb-item">Upload Jadwal Lain</div>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card upload-card">
                                    <div class="card-header">
                                        <h4>Upload File Excel Jadwal Lainnya</h4>
                                        <p class="text-muted mb-0">Data jadwal tambahan di luar jadwal utama</p>
                                    </div>
                                    <div class="card-body">
                                        <!-- Upload Stats -->
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <div class="upload-stats bg-light">
                                                    <div class="stat-item">
                                                        <div class="stat-number">
                                                            <?php
                                                            $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_pnt");
                                                            $row = mysqli_fetch_assoc($query);
                                                            echo $row['total'];
                                                            ?>
                                                        </div>
                                                        <div class="stat-label">Total Data</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="upload-stats bg-light">
                                                    <div class="stat-item">
                                                        <div class="stat-number">
                                                            <?php
                                                            $query = mysqli_query($koneksi, "SELECT COUNT(DISTINCT jbtn_pnt) as total FROM t_pnt");
                                                            $row = mysqli_fetch_assoc($query);
                                                            echo $row['total'];
                                                            ?>
                                                        </div>
                                                        <div class="stat-label">Jabatan Unik</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="upload-stats bg-light">
                                                    <div class="stat-item">
                                                        <div class="stat-number"><?php echo date('d'); ?></div>
                                                        <div class="stat-label">Hari Ini</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="upload-stats bg-light">
                                                    <div class="stat-item">
                                                        <div class="stat-number">
                                                            <?php
                                                            $month = date('m');
                                                            $year = date('Y');
                                                            $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_pnt WHERE MONTH(created_at) = $month AND YEAR(created_at) = $year");
                                                            $row = mysqli_fetch_assoc($query);
                                                            echo $row['total'];
                                                            ?>
                                                        </div>
                                                        <div class="stat-label">Bulan Ini</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="process.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="filexls" class="form-label fw-bold">Pilih File Excel</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-file-excel"></i>
                                                            </span>
                                                            <input type="file" class="form-control" id="filexls" name="filexls" accept=".xls,.xlsx" required>
                                                        </div>
                                                        <div class="form-text">
                                                            Drag & drop file atau klik untuk memilih
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Drag & Drop Area -->
                                                    <div class="border-dashed rounded p-4 text-center mb-4 d-none" id="dropArea">
                                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                        <h5>Drop file Excel di sini</h5>
                                                        <p class="text-muted">atau klik untuk memilih</p>
                                                        <input type="file" class="d-none" id="fileDrop" accept=".xls,.xlsx">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="file-info">
                                                        <h6><i class="fas fa-info-circle me-2"></i>Format File Excel:</h6>
                                                        <ul class="mb-2">
                                                            <li>Kolom B: Jabatan Panitia</li>
                                                            <li>Kolom C: Honor Standard</li>
                                                            <li>Kolom D: Honor P1</li>
                                                            <li>Kolom E: Honor P2</li>
                                                        </ul>
                                                        <div class="alert alert-info py-2">
                                                            <small>
                                                                <i class="fas fa-lightbulb me-1"></i>
                                                                Data duplikat (berdasarkan jabatan) tidak akan diimport
                                                            </small>
                                                        </div>
                                                        <div class="mt-3">
                                                            <a href="#" class="template-link me-3">
                                                                <i class="fas fa-download me-1"></i> Download Template
                                                            </a>
                                                            <a href="#" class="template-link">
                                                                <i class="fas fa-question-circle me-1"></i> Panduan Format
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Advanced Options -->
                                            <div class="accordion mb-4" id="advancedOptions">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOptions">
                                                            <i class="fas fa-sliders-h me-2"></i> Opsi Lanjutan
                                                        </button>
                                                    </h2>
                                                    <div id="collapseOptions" class="accordion-collapse collapse" data-bs-parent="#advancedOptions">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" id="updateExisting" name="update_existing">
                                                                        <label class="form-check-label" for="updateExisting">
                                                                            Update data yang sudah ada
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="sendNotification" name="send_notification">
                                                                        <label class="form-check-label" for="sendNotification">
                                                                            Kirim notifikasi setelah upload
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Kategori</label>
                                                                        <select class="form-select" name="category">
                                                                            <option value="">Pilih Kategori</option>
                                                                            <option value="reguler">Reguler</option>
                                                                            <option value="khusus">Khusus</option>
                                                                            <option value="tambahan">Tambahan</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                                                    <i class="fas fa-home me-2"></i>Dashboard
                                                </a>
                                                <div>
                                                    <button type="button" class="btn btn-outline-success me-2" id="validateBtn">
                                                        <i class="fas fa-check-circle me-2"></i>Validasi File
                                                    </button>
                                                    <button type="submit" name="submit" class="btn btn-upload">
                                                        <i class="fas fa-rocket me-2"></i>Upload & Proses
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <small class="text-muted">
                                                    <i class="fas fa-shield-alt me-1"></i>
                                                    File akan divalidasi sebelum diproses untuk mencegah duplikasi
                                                </small>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <small class="text-muted">
                                                    Versi: 1.0.0 | 
                                                    <i class="fas fa-user-shield me-1"></i>
                                                    <?php echo $_SESSION['username'] ?? 'Admin'; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Log -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Log Sistem Upload</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Waktu</th>
                                                        <th>Aktivitas</th>
                                                        <th>File</th>
                                                        <th>Status</th>
                                                        <th>Catatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><?php echo date('d/m/Y H:i:s'); ?></td>
                                                        <td>Akses halaman</td>
                                                        <td>-</td>
                                                        <td><span class="badge bg-info">Info</span></td>
                                                        <td>Admin mengakses halaman upload</td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo date('d/m/Y 08:30:00'); ?></td>
                                                        <td>Upload data</td>
                                                        <td>jadwal_semester_20241.xlsx</td>
                                                        <td><span class="badge bg-success">Sukses</span></td>
                                                        <td>15 data berhasil diimport</td>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Drag and drop functionality
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('filexls');
        const fileDrop = document.getElementById('fileDrop');
        
        if (dropArea) {
            // Show drop area on hover
            fileInput.addEventListener('mouseenter', function() {
                dropArea.classList.remove('d-none');
            });
            
            // Handle drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                dropArea.classList.add('bg-light');
            }
            
            function unhighlight() {
                dropArea.classList.remove('bg-light');
            }
            
            dropArea.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                
                // Trigger change event
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
            
            // Click to select file
            dropArea.addEventListener('click', function() {
                fileDrop.click();
            });
            
            fileDrop.addEventListener('change', function() {
                fileInput.files = fileDrop.files;
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            });
        }
        
        // Validate button
        document.getElementById('validateBtn').addEventListener('click', function() {
            if (fileInput.files.length === 0) {
                alert('Silakan pilih file terlebih dahulu');
                return;
            }
            
            // Simulate validation
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.innerHTML = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        <div>Memvalidasi file...</div>
                    </div>
                </div>
            `;
            
            setTimeout(() => {
                messageContainer.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        File valid dan siap diupload. Tidak ditemukan masalah format.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
            }, 1500);
        });
        
        // Form validation
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            if (fileInput.files.length === 0) {
                e.preventDefault();
                alert('Silakan pilih file terlebih dahulu');
                return false;
            }
        });
    </script>
</body>
</html>