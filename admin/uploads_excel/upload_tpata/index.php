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
    <title>Upload Panitia PA/TA - SiPagu Admin</title>
    
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
            border-left: 4px solid #4cc9f0;
            padding: 15px;
            border-radius: 5px;
        }
        .btn-upload {
            background: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
            border: none;
            padding: 10px 30px;
            font-weight: 600;
        }
        .btn-upload:hover {
            background: linear-gradient(135deg, #4895ef 0%, #4cc9f0 100%);
        }
        .template-link {
            color: #4cc9f0;
            text-decoration: none;
            font-weight: 500;
        }
        .template-link:hover {
            text-decoration: underline;
        }
        .preview-box {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 20px;
            background: #f8f9fa;
            min-height: 200px;
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
                        <h1>Upload Panitia PA/TA</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="../../index.php">Dashboard</a></div>
                            <div class="breadcrumb-item">Uploads Excel</div>
                            <div class="breadcrumb-item">Upload Panitia PA/TA</div>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card upload-card">
                                    <div class="card-header">
                                        <h4>Upload File Excel Panitia Proposal/Tugas Akhir</h4>
                                        <p class="text-muted mb-0">Data panitia bimbingan proposal dan tugas akhir</p>
                                    </div>
                                    <div class="card-body">
                                        <form action="process.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="filexls" class="form-label fw-bold">Pilih File Excel</label>
                                                        <div class="input-group">
                                                            <input type="file" class="form-control" id="filexls" name="filexls" accept=".xls,.xlsx" required>
                                                            <button class="btn btn-outline-secondary" type="button" id="clearFile">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                        <div class="form-text">
                                                            Header pada baris pertama akan dilewati
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Optional Settings -->
                                                    <div class="card">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Pengaturan</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox" id="skipDuplicates" checked>
                                                                <label class="form-check-label" for="skipDuplicates">
                                                                    Lewati data duplikat
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="validateData" checked>
                                                                <label class="form-check-label" for="validateData">
                                                                    Validasi data sebelum upload
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="file-info">
                                                        <h6><i class="fas fa-info-circle me-2"></i>Format File Excel:</h6>
                                                        <ul class="mb-2">
                                                            <li>Kolom B: Semester</li>
                                                            <li>Kolom C: Periode Wisuda</li>
                                                            <li>Kolom D: ID User</li>
                                                            <li>Kolom E: Program Studi</li>
                                                            <li>Kolom F: Jml Mhs Prodi</li>
                                                            <li>Kolom G: Jml Mhs Bimbingan</li>
                                                            <li>Kolom H: Jml PGJI 1</li>
                                                            <li>Kolom I: Jml PGJI 2</li>
                                                            <li>Kolom J: Ketua PGJI</li>
                                                        </ul>
                                                        <div class="alert alert-warning py-2">
                                                            <small>
                                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                                Data dengan semester atau id_user kosong akan dilewati
                                                            </small>
                                                        </div>
                                                        <div class="mt-3">
                                                            <a href="#" class="template-link">
                                                                <i class="fas fa-download me-1"></i> Download Template
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Preview Area -->
                                            <div class="row mt-3 d-none" id="previewRow">
                                                <div class="col-12">
                                                    <div class="preview-box">
                                                        <h6 class="mb-3"><i class="fas fa-eye me-2"></i>Preview Data</h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm" id="previewTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Semester</th>
                                                                        <th>Periode</th>
                                                                        <th>ID User</th>
                                                                        <th>Prodi</th>
                                                                        <th>Mhs Prodi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!-- Preview data akan dimuat di sini via JavaScript -->
                                                                </tbody>
                                                            </table>
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
                                                <div>
                                                    <a href="../../index.php" class="btn btn-outline-secondary me-2">
                                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                                    </a>
                                                    <button type="button" class="btn btn-outline-primary" id="previewBtn">
                                                        <i class="fas fa-eye me-2"></i>Preview
                                                    </button>
                                                </div>
                                                <button type="submit" name="submit" class="btn btn-upload">
                                                    <i class="fas fa-upload me-2"></i>Upload Data PA/TA
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-database me-1"></i>
                                                    Total data di sistem: 
                                                    <?php
                                                    $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_panitia");
                                                    $row = mysqli_fetch_assoc($query);
                                                    echo $row['total'];
                                                    ?>
                                                </small>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <small class="text-muted">
                                                    <i class="fas fa-history me-1"></i>
                                                    Update: <?php echo date('d/m/Y H:i'); ?>
                                                </small>
                                            </div>
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
        // Clear file input
        document.getElementById('clearFile').addEventListener('click', function() {
            document.getElementById('filexls').value = '';
            document.getElementById('previewRow').classList.add('d-none');
        });
        
        // File validation and preview
        document.getElementById('filexls').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const messageContainer = document.getElementById('messageContainer');
            
            if (file) {
                const fileName = file.name;
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
                    document.getElementById('previewRow').classList.add('d-none');
                } else {
                    messageContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            File <strong>${fileName}</strong> berhasil dipilih
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    document.getElementById('previewRow').classList.remove('d-none');
                }
            }
        });
        
        // Preview button
        document.getElementById('previewBtn').addEventListener('click', function() {
            const fileInput = document.getElementById('filexls');
            if (fileInput.files.length === 0) {
                alert('Silakan pilih file terlebih dahulu');
                return;
            }
            alert('Fitur preview sedang dalam pengembangan');
        });
    </script>
</body>
</html>