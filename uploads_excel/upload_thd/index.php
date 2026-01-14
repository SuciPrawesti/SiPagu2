<?php
/**
 * UPLOAD JADWAL - SiPagu
 * Lokasi: admin/uploads_excel/upload_thd/index.php
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
            <h1>Upload Jadwal</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Uploads Excel</a></div>
                <div class="breadcrumb-item active">Upload Jadwal</div>
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
                                    <a href="<?= BASE_URL ?>admin/index.php" class="btn btn-outline-secondary">
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
                                    Admin: <?php echo htmlspecialchars($_SESSION['username'] ?? 'System'); ?>
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

<?php include __DIR__ . '/../../includes/footer.php'; ?>
<?php include __DIR__ . '/../../includes/footer_script.php'; ?>

<!-- Page Specific JavaScript -->
<script>
// Form validation
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('filexls');
    const messageContainer = document.getElementById('messageContainer');
    
    if (fileInput.files.length === 0) {
        e.preventDefault();
        messageContainer.innerHTML = `<?= showAlert('danger', "Silakan pilih file Excel terlebih dahulu") ?>`;
        fileInput.focus();
        return false;
    }
    
    const file = fileInput.files[0];
    const fileExt = file.name.split('.').pop().toLowerCase();
    
    if (!['xls', 'xlsx'].includes(fileExt)) {
        e.preventDefault();
        messageContainer.innerHTML = `<?= showAlert('danger', "Format file tidak valid. Harap pilih file Excel (.xls atau .xlsx)") ?>`;
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