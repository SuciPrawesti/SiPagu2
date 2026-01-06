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
    <title>Upload Jadwal - SiPagu Admin</title>
    
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
            border-left: 4px solid #f8961e;
            padding: 15px;
            border-radius: 5px;
        }
        .btn-upload {
            background: linear-gradient(135deg, #f8961e 0%, #f3722c 100%);
            border: none;
            padding: 10px 30px;
            font-weight: 600;
        }
        .btn-upload:hover {
            background: linear-gradient(135deg, #f3722c 0%, #f8961e 100%);
        }
        .template-link {
            color: #f8961e;
            text-decoration: none;
            font-weight: 500;
        }
        .template-link:hover {
            text-decoration: underline;
        }
        .calendar-icon {
            color: #f8961e;
            font-size: 2rem;
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
                        <h1>Upload Jadwal</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="../../index.php">Dashboard</a></div>
                            <div class="breadcrumb-item">Uploads Excel</div>
                            <div class="breadcrumb-item">Upload Jadwal</div>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card upload-card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center">
                                            <div class="calendar-icon me-3">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <div>
                                                <h4 class="mb-0">Upload File Excel Jadwal</h4>
                                                <p class="text-muted mb-0">Data jadwal perkuliahan per bulan</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form action="process.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="filexls" class="form-label fw-bold">Pilih File Excel</label>
                                                        <input type="file" class="form-control" id="filexls" name="filexls" accept=".xls,.xlsx" required>
                                                        <div class="form-text">
                                                            Data akan mulai diproses dari baris ke-2
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Quick Stats -->
                                                    <div class="row g-3 mb-4">
                                                        <div class="col-6">
                                                            <div class="card bg-light">
                                                                <div class="card-body text-center py-3">
                                                                    <h6 class="mb-1">Total Jadwal</h6>
                                                                    <h4 class="text-primary mb-0">
                                                                        <?php
                                                                        $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM t_jadwal");
                                                                        $row = mysqli_fetch_assoc($query);
                                                                        echo $row['total'];
                                                                        ?>
                                                                    </h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="card bg-light">
                                                                <div class="card-body text-center py-3">
                                                                    <h6 class="mb-1">Semester Aktif</h6>
                                                                    <h4 class="text-success mb-0">20241</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="file-info">
                                                        <h6><i class="fas fa-info-circle me-2"></i>Format File Excel:</h6>
                                                        <ul class="mb-0">
                                                            <li>Kolom B: Semester</li>
                                                            <li>Kolom C: Bulan</li>
                                                            <li>Kolom D: Jumlah TM</li>
                                                            <li>Kolom E: SKS Tempuh</li>
                                                        </ul>
                                                        <div class="mt-3">
                                                            <a href="#" class="template-link">
                                                                <i class="fas fa-download me-1"></i> Download Template
                                                            </a>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Note -->
                                                    <div class="alert alert-light mt-3">
                                                        <h6><i class="fas fa-lightbulb me-2"></i>Catatan:</h6>
                                                        <ul class="mb-0">
                                                            <li>Baris dengan semester/bulan kosong akan dilewati</li>
                                                            <li>Data duplikat (semester + bulan) akan dilewati</li>
                                                            <li>Pastikan format bulan sesuai (Januari, Februari, ...)</li>
                                                        </ul>
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
                                                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                                                </a>
                                                <div>
                                                    <button type="reset" class="btn btn-outline-warning me-2">
                                                        <i class="fas fa-redo me-2"></i>Reset
                                                    </button>
                                                    <button type="submit" name="submit" class="btn btn-upload">
                                                        <i class="fas fa-upload me-2"></i>Upload Jadwal
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                Upload terakhir: 
                                                <?php
                                                $query = mysqli_query($koneksi, "SELECT MAX(created_at) as last_upload FROM t_jadwal");
                                                $row = mysqli_fetch_assoc($query);
                                                echo $row['last_upload'] ? date('d/m/Y H:i', strtotime($row['last_upload'])) : 'Belum ada';
                                                ?>
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                Admin: <?php echo $_SESSION['username'] ?? 'System'; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Schedule -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Jadwal Terkini</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Semester</th>
                                                        <th>Bulan</th>
                                                        <th>Jumlah TM</th>
                                                        <th>SKS Tempuh</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query = mysqli_query($koneksi, "SELECT * FROM t_jadwal ORDER BY semester DESC, bulan DESC LIMIT 5");
                                                    if (mysqli_num_rows($query) > 0) {
                                                        while ($row = mysqli_fetch_assoc($query)) {
                                                            echo '<tr>';
                                                            echo '<td>' . htmlspecialchars($row['semester']) . '</td>';
                                                            echo '<td>' . htmlspecialchars($row['bulan']) . '</td>';
                                                            echo '<td>' . $row['jml_tm'] . '</td>';
                                                            echo '<td>' . $row['sks_tempuh'] . '</td>';
                                                            echo '<td><span class="badge bg-success">Aktif</span></td>';
                                                            echo '</tr>';
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="5" class="text-center text-muted">Belum ada data jadwal</td></tr>';
                                                    }
                                                    ?>
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
        // Form validation
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('filexls');
            const messageContainer = document.getElementById('messageContainer');
            
            if (fileInput.files.length === 0) {
                e.preventDefault();
                messageContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Silakan pilih file Excel terlebih dahulu
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                fileInput.focus();
                return false;
            }
            
            const file = fileInput.files[0];
            const fileExt = file.name.split('.').pop().toLowerCase();
            
            if (!['xls', 'xlsx'].includes(fileExt)) {
                e.preventDefault();
                messageContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Format file tidak valid. Harap pilih file Excel (.xls atau .xlsx)
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                return false;
            }
            
            // Show loading
            messageContainer.innerHTML = `
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        <div>Sedang memproses file...</div>
                    </div>
                </div>
            `;
        });
    </script>
</body>
</html>