<?php
/**
 * UPLOAD DATA PANITIA - SiPagu
 * Halaman untuk upload data panitia dari Excel
 * Lokasi: admin/upload_panitia.php
 */

// Include required files
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config.php';

// Set page title
$page_title = "Upload Data Panitia";

// Process form submission
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Validasi file
    if (empty($_FILES['filexls']['name'])) {
        $error_message = 'Silakan pilih file Excel.';
    } else {
        $file_name = $_FILES['filexls']['name'];
        $file_tmp = $_FILES['filexls']['tmp_name'];
        $file_size = $_FILES['filexls']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validasi ekstensi file
        $allowed_ext = ['xls', 'xlsx'];
        if (!in_array($file_ext, $allowed_ext)) {
            $error_message = 'File harus bertipe XLS atau XLSX.';
        }
        // Validasi ukuran file (10MB max)
        elseif ($file_size > 10 * 1024 * 1024) {
            $error_message = 'File terlalu besar. Maksimal 10MB.';
        }
        else {
            // Include PhpSpreadsheet
            require_once __DIR__ . '/../vendor/autoload.php';
            
            try {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_tmp);
                $spreadsheet = $reader->load($file_tmp);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                
                $jumlahData = 0;
                $jumlahGagal = 0;
                $errors = [];
                
                // Mulai dari baris 1 (baris 0 = header)
                for ($i = 1; $i < count($sheetData); $i++) {
                    $jbtn_pnt  = trim($sheetData[$i][0] ?? '');
                    $honor_std = trim($sheetData[$i][1] ?? '0');
                    $honor_p1  = trim($sheetData[$i][2] ?? '0');
                    $honor_p2  = trim($sheetData[$i][3] ?? '0');
                    
                    // Skip baris kosong
                    if (empty($jbtn_pnt)) {
                        $jumlahGagal++;
                        continue;
                    }
                    
                    // Validasi angka honor
                    if (!is_numeric($honor_std) || !is_numeric($honor_p1) || !is_numeric($honor_p2)) {
                        $errors[] = "Baris $i: Honor harus berupa angka";
                        $jumlahGagal++;
                        continue;
                    }
                    
                    // Cek apakah jabatan sudah ada
                    $cek = mysqli_query($koneksi,
                        "SELECT id_pnt FROM t_panitia WHERE jbtn_pnt = '$jbtn_pnt'"
                    );
                    
                    if (mysqli_num_rows($cek) > 0) {
                        // Jika opsi timpa aktif
                        if (isset($_POST['overwrite']) && $_POST['overwrite'] == '1') {
                            $update = mysqli_query($koneksi, "
                                UPDATE t_panitia SET
                                    honor_std = '$honor_std',
                                    honor_p1 = '$honor_p1',
                                    honor_p2 = '$honor_p2'
                                WHERE jbtn_pnt = '$jbtn_pnt'
                            ");
                            
                            if ($update) $jumlahData++;
                            else $jumlahGagal++;
                        } else {
                            $errors[] = "Baris $i: Jabatan '$jbtn_pnt' sudah ada (gunakan opsi 'Timpa data')";
                            $jumlahGagal++;
                        }
                        continue;
                    }
                    
                    // Insert data baru
                    $insert = mysqli_query($koneksi, "
                        INSERT INTO t_panitia 
                        (jbtn_pnt, honor_std, honor_p1, honor_p2)
                        VALUES
                        ('$jbtn_pnt', '$honor_std', '$honor_p1', '$honor_p2')
                    ");
                    
                    if ($insert) {
                        $jumlahData++;
                    } else {
                        $errors[] = "Baris $i: Gagal menyimpan data '$jbtn_pnt'";
                        $jumlahGagal++;
                    }
                }
                
                if ($jumlahData > 0) {
                    $success_message = "Berhasil mengimport <strong>$jumlahData</strong> data panitia.";
                    if ($jumlahGagal > 0) {
                        $success_message .= " <strong>$jumlahGagal</strong> data gagal.";
                    }
                    if (!empty($errors)) {
                        $error_message = implode('<br>', array_slice($errors, 0, 5));
                        if (count($errors) > 5) {
                            $error_message .= '<br>... dan ' . (count($errors) - 5) . ' error lainnya';
                        }
                    }
                } else {
                    $error_message = "Tidak ada data yang berhasil diimport.";
                    if (!empty($errors)) {
                        $error_message .= '<br>' . implode('<br>', array_slice($errors, 0, 5));
                    }
                }
                
            } catch (Exception $e) {
                $error_message = "Terjadi kesalahan saat membaca file: " . $e->getMessage();
            }
        }
    }
}

// Include header
include __DIR__ . '/includes/header.php';

// Include navbar
include __DIR__ . '/includes/navbar.php';

// Include sidebar
include __DIR__ . '/includes/sidebar_admin.php';
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Data Panitia</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item">Upload Data Panitia</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Display Messages -->
            <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= $error_message ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= $success_message ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Upload File Excel</h4>
                        </div>
                        <div class="card-body">
                            <!-- Instructions -->
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle mr-2"></i>Petunjuk Penggunaan</h6>
                                <ul class="mb-0 pl-3">
                                    <li>Pastikan file Excel memiliki kolom sesuai template</li>
                                    <li>Kolom wajib: Jabatan Panitia, Honor Standar, Honor Periode 1, Honor Periode 2</li>
                                    <li>Honor harus dalam angka tanpa titik atau koma</li>
                                    <li>Data duplikat berdasarkan jabatan akan diabaikan</li>
                                    <li>Pastikan format honor konsisten untuk semua data</li>
                                </ul>
                            </div>

                            <!-- Template Download -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4><i class="fas fa-download mr-2"></i>Download Template</h4>
                                </div>
                                <div class="card-body">
                                    <p>Gunakan template ini untuk memastikan format file Excel sesuai dengan sistem.</p>
                                    <a href="#" class="btn btn-primary btn-icon icon-left">
                                        <i class="fas fa-download"></i> Download Template Panitia.xlsx
                                    </a>
                                </div>
                            </div>

                            <!-- Upload Form -->
                            <form action="" method="POST" enctype="multipart/form-data" class="mt-4">
                                <div class="form-group">
                                    <label>Pilih File Excel</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="filexls" name="filexls" accept=".xls,.xlsx" required>
                                        <label class="custom-file-label" for="filexls">Pilih file...</label>
                                    </div>
                                    <small class="form-text text-muted">Format: .xls atau .xlsx (maks. 10MB)</small>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Semester</label>
                                        <select class="form-control" name="semester">
                                            <option value="">Pilih Semester</option>
                                            <option value="20241">2024 Ganjil (20241)</option>
                                            <option value="20242">2024 Genap (20242)</option>
                                            <option value="20251">2025 Ganjil (20251)</option>
                                            <option value="20252">2025 Genap (20252)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <div class="custom-control custom-checkbox mt-4 pt-2">
                                            <input type="checkbox" class="custom-control-input" id="overwrite" name="overwrite" value="1">
                                            <label class="custom-control-label" for="overwrite">Timpa data yang sudah ada</label>
                                            <small class="form-text text-muted">Jika dicentang, data dengan jabatan yang sama akan ditimpa</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-primary btn-icon icon-left">
                                        <i class="fas fa-upload"></i> Upload dan Proses
                                    </button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </form>

                            <!-- Manual Input Form -->
                            <div class="mt-5">
                                <h5><i class="fas fa-keyboard mr-2"></i>Input Manual</h5>
                                <form action="" method="POST" class="mt-3">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Jabatan Panitia <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="manual_jbtn" placeholder="Ketua Panitia" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Honor Standar <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="manual_honor_std" placeholder="500000" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Honor Periode 1 <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="manual_honor_p1" placeholder="750000" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Honor Periode 2 <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="manual_honor_p2" placeholder="1000000" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="submit" name="submit_manual" class="btn btn-success btn-icon icon-left">
                                            <i class="fas fa-save"></i> Simpan Data Manual
                                        </button>
                                    </div>
                                </form>
                                
                                <?php
                                // Process manual input
                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_manual'])) {
                                    $manual_jbtn = mysqli_real_escape_string($koneksi, $_POST['manual_jbtn']);
                                    $manual_honor_std = mysqli_real_escape_string($koneksi, $_POST['manual_honor_std']);
                                    $manual_honor_p1 = mysqli_real_escape_string($koneksi, $_POST['manual_honor_p1']);
                                    $manual_honor_p2 = mysqli_real_escape_string($koneksi, $_POST['manual_honor_p2']);
                                    
                                    // Validasi angka
                                    if (!is_numeric($manual_honor_std) || !is_numeric($manual_honor_p1) || !is_numeric($manual_honor_p2)) {
                                        echo '<div class="alert alert-danger">Semua honor harus berupa angka!</div>';
                                    } else {
                                        // Check if jabatan already exists
                                        $check = mysqli_query($koneksi, "SELECT id_pnt FROM t_panitia WHERE jbtn_pnt = '$manual_jbtn'");
                                        
                                        if (mysqli_num_rows($check) > 0) {
                                            echo '<div class="alert alert-warning">Jabatan sudah terdaftar!</div>';
                                        } else {
                                            $insert_manual = mysqli_query($koneksi, "
                                                INSERT INTO t_panitia 
                                                (jbtn_pnt, honor_std, honor_p1, honor_p2)
                                                VALUES
                                                ('$manual_jbtn', '$manual_honor_std', '$manual_honor_p1', '$manual_honor_p2')
                                            ");
                                            
                                            if ($insert_manual) {
                                                echo '<div class="alert alert-success">Data panitia berhasil disimpan!</div>';
                                            } else {
                                                echo '<div class="alert alert-danger">Gagal menyimpan data: ' . mysqli_error($koneksi) . '</div>';
                                            }
                                        }
                                    }
                                }
                                ?>
                            </div>

                            <!-- Preview Data -->
                            <div class="mt-5">
                                <h5><i class="fas fa-table mr-2"></i>Data Panitia Terbaru</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Jabatan</th>
                                                <th>Honor Standar</th>
                                                <th>Honor Periode 1</th>
                                                <th>Honor Periode 2</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = mysqli_query($koneksi, 
                                                "SELECT jbtn_pnt, honor_std, honor_p1, honor_p2 
                                                 FROM t_panitia 
                                                 ORDER BY id_pnt DESC 
                                                 LIMIT 10"
                                            );
                                            while ($row = mysqli_fetch_assoc($query)): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($row['jbtn_pnt']) ?></strong></td>
                                                <td>Rp <?= number_format($row['honor_std'], 0, ',', '.') ?></td>
                                                <td>Rp <?= number_format($row['honor_p1'], 0, ',', '.') ?></td>
                                                <td>Rp <?= number_format($row['honor_p2'], 0, ',', '.') ?></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- End Main Content -->

<?php 
// Include footer
include __DIR__ . '/includes/footer.php';

// Include footer scripts
include __DIR__ . '/includes/footer_scripts.php';
?>