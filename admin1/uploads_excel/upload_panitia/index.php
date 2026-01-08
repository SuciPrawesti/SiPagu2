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
    <title>Upload Data Panitia - SiPagu Admin</title>
    
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
            border-left: 4px solid #7209b7;
            padding: 15px;
            border-radius: 5px;
        }
        .btn-upload {
            background: linear-gradient(135deg, #7209b7 0%, #560bad 100%);
            border: none;
            padding: 10px 30px;
            font-weight: 600;
        }
        .btn-upload:hover {
            background: linear-gradient(135deg, #560bad 0%, #7209b7 100%);
        }
        .template-link {
            color: #7209b7;
            text-decoration: none;
            font-weight: 500;
        }
        .template-link:hover {
            text-decoration: underline;
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
                        <h1>Upload Data Panitia</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="../../index.php">Dashboard</a></div>
                            <div class="breadcrumb-item">Uploads Excel</div>
                            <div class="breadcrumb-item">Upload Data Panitia</div>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card upload-card">
                                    <div class="card-header">
                                        <h4>Upload File Excel Data Panitia</h4>
                                        <p class="text-muted mb-0">Data honor panitia ujian</p>
                                    </div>
                                    <div class="card-body">
                                        <form action="process.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="filexls" class="form-label fw-bold">Pilih File Excel</label>
                                                        <input type="file" class="form-control" id="filexls" name="filexls" accept=".xls,.xlsx" required>
                                                        <div class="form-text">
                                                            Format file: .xls atau .xlsx
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="file-info">
                                                        <h6><i class="fas fa-info-circle me-2"></i>Format File Excel:</h6>
                                                        <ul class="mb-0">
                                                            <li>Kolom B: Jabatan Panitia</li>
                                                            <li>Kolom C: Honor Standard</li>
                                                            <li>Kolom D: Honor P1</li>
                                                            <li>Kolom E: Honor P2</li>
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
                                // Tampilkan pesan dari session jika ada
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
                                                    <i class="fas fa-upload me-2"></i>Upload Data Panitia
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <small class="text-muted">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            Data duplikat (berdasarkan jabatan) akan dilewati.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Preview -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4>Data Panitia Terakhir</h4>
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Lihat Semua
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Jabatan</th>
                                                        <th>Honor Standard</th>
                                                        <th>Honor P1</th>
                                                        <th>Honor P2</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Query data panitia terakhir
                                                    $query = mysqli_query($koneksi, "SELECT * FROM t_pnt ORDER BY id_pnt DESC LIMIT 5");
                                                    if (mysqli_num_rows($query) > 0) {
                                                        while ($row = mysqli_fetch_assoc($query)) {
                                                            echo '<tr>';
                                                            echo '<td>' . htmlspecialchars($row['jbtn_pnt']) . '</td>';
                                                            echo '<td>' . number_format($row['honor_std'], 0, ',', '.') . '</td>';
                                                            echo '<td>' . number_format($row['honor_p1'], 0, ',', '.') . '</td>';
                                                            echo '<td>' . number_format($row['honor_p2'], 0, ',', '.') . '</td>';
                                                            echo '<td><span class="badge bg-success">Aktif</span></td>';
                                                            echo '</tr>';
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="5" class="text-center text-muted">Belum ada data panitia</td></tr>';
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
        // File validation
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
                } else {
                    messageContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            File <strong>${fileName}</strong> siap diupload
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }
            }
        });
    </script>
</body>
</html>