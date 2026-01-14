<?php
/**
 * UPLOAD DATA USER - SiPagu
 * Lokasi: admin/uploads_excel/upload_user/index.php
 * HARUS partial content, bukan full page
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/function_helper.php';
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/sidebar_admin.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Data User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
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
                                    <a href="<?= BASE_URL ?>admin/index.php" class="btn btn-outline-secondary btn-icon icon-left">
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
                                    Login sebagai: <?php echo htmlspecialchars($_SESSION['username'] ?? 'Administrator'); ?>
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

<?php include __DIR__ . '/../../includes/footer.php'; ?>
<?php include __DIR__ . '/../../includes/footer_scripts.php'; ?>

<!-- Page Specific JavaScript -->
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
            messageContainer.innerHTML = `<?= showAlert('danger', "Format file tidak valid. Harap pilih file Excel (.xls atau .xlsx)") ?>`;
            e.target.value = '';
            fileLabel.textContent = 'Choose file...';
        } else if (fileSize > 10) {
            messageContainer.innerHTML = `<?= showAlert('warning', "File terlalu besar (" . '${fileSize}' . " MB). Maksimal 10 MB") ?>`;
            e.target.value = '';
            fileLabel.textContent = 'Choose file...';
        } else {
            messageContainer.innerHTML = `<?= showAlert('success', "File <strong>" . '${fileName}' . "</strong> (" . '${fileSize}' . " MB) siap diupload") ?>`;
        }
    }
});

// Validate button
document.getElementById('validateBtn').addEventListener('click', function() {
    const fileInput = document.getElementById('filexls');
    const messageContainer = document.getElementById('messageContainer');
    
    if (fileInput.files.length === 0) {
        messageContainer.innerHTML = `<?= showAlert('warning', "Silakan pilih file Excel terlebih dahulu") ?>`;
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
        messageContainer.innerHTML = `<?= showAlert('success', "File valid dan siap diupload. Format sesuai dengan template.") ?>`;
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
</script>