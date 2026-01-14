<?php
/**
 * UPLOAD TRANSAKSI UJIAN - SiPagu
 * Lokasi: admin/uploads_excel/upload_tu/index.php
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
            <h1>Upload Transaksi Ujian</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Uploads Excel</a></div>
                <div class="breadcrumb-item active">Upload Transaksi Ujian</div>
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
                                    <a href="<?= BASE_URL ?>admin/index.php" class="btn btn-outline-secondary">
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

<?php include __DIR__ . '/../../includes/footer.php'; ?>
<?php include __DIR__ . '/../../includes/footer_scripts.php'; ?>

<!-- Page Specific JavaScript -->
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
            messageContainer.innerHTML = `<?= showAlert('danger', "Format file tidak valid. Harap pilih file Excel (.xls atau .xlsx)") ?>`;
            e.target.value = '';
        } else if (fileSize > 10) {
            messageContainer.innerHTML = `<?= showAlert('warning', "File terlalu besar (" . '${fileSize}' . " MB). Maksimal 10 MB") ?>`;
            e.target.value = '';
        } else {
            messageContainer.innerHTML = `<?= showAlert('success', "File <strong>" . '${fileName}' . "</strong> (" . '${fileSize}' . " MB) siap diupload") ?>`;
        }
    }
});
</script>