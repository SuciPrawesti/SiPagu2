<?php
/**
 * UPLOAD DATA PANITIA - SiPagu
 * Lokasi: admin/uploads_excel/upload_panitia/index.php
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
            <h1>Upload Data Panitia</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Uploads Excel</a></div>
                <div class="breadcrumb-item active">Upload Data Panitia</div>
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
                                        $query = mysqli_query($koneksi, "SELECT * FROM t_panitia ORDER BY id_pnt DESC LIMIT 5");
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

<?php include __DIR__ . '/../../includes/footer.php'; ?>
<?php include __DIR__ . '/../../includes/footer_script.php'; ?>

<!-- Page Specific JavaScript -->
<script>
// File validation
document.getElementById('filexls').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const messageContainer = document.getElementById('messageContainer');
    
    if (file) {
        const fileName = file.name;
        const fileExt = fileName.split('.').pop().toLowerCase();
        
        if (!['xls', 'xlsx'].includes(fileExt)) {
            messageContainer.innerHTML = `<?= showAlert('danger', "Format file tidak valid. Harap pilih file Excel (.xls atau .xlsx)") ?>`;
            e.target.value = '';
        } else {
            messageContainer.innerHTML = `<?= showAlert('success', "File <strong>" . '${fileName}' . "</strong> siap diupload") ?>`;
        }
    }
});
</script>