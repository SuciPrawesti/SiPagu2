<?php
/**
 * UPLOAD TRANSAKSI UJIAN - SiPagu
 * Halaman untuk upload data transaksi ujian dari Excel
 * Lokasi: admin/upload_tu.php
 */

// Include required files
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config.php';

// Set page title
$page_title = "Upload Transaksi Ujian";

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
                    $semester         = trim($sheetData[$i][0] ?? '');
                    $id_panitia       = trim($sheetData[$i][1] ?? '');
                    $id_user          = trim($sheetData[$i][2] ?? '');
                    $jml_mhs_prodi    = trim($sheetData[$i][3] ?? '0');
                    $jml_mhs          = trim($sheetData[$i][4] ?? '0');
                    $jml_koreksi      = trim($sheetData[$i][5] ?? '0');
                    $jml_matkul       = trim($sheetData[$i][6] ?? '0');
                    $jml_pgws_pagi    = trim($sheetData[$i][7] ?? '0');
                    $jml_pgws_sore    = trim($sheetData[$i][8] ?? '0');
                    $jml_koor_pagi    = trim($sheetData[$i][9] ?? '0');
                    $jml_koor_sore    = trim($sheetData[$i][10] ?? '0');
                    
                    // Skip baris kosong
                    if (empty($semester) || empty($id_panitia) || empty($id_user)) {
                        $jumlahGagal++;
                        continue;
                    }
                    
                    // Validasi semester format
                    if (!preg_match('/^\d{4}[12]$/', $semester)) {
                        $errors[] = "Baris $i: Format semester '$semester' tidak valid (contoh: 20241)";
                        $jumlahGagal++;
                        continue;
                    }
                    
                    // Validasi angka
                    $numeric_fields = ['jml_mhs_prodi', 'jml_mhs', 'jml_koreksi', 'jml_matkul', 
                                       'jml_pgws_pagi', 'jml_pgws_sore', 'jml_koor_pagi', 'jml_koor_sore'];
                    foreach ($numeric_fields as $field) {
                        if (!is_numeric($$field) || $$field < 0) {
                            $errors[] = "Baris $i: Kolom $field harus angka positif";
                            $jumlahGagal++;
                            continue 2;
                        }
                    }
                    
                    // Cek apakah kombinasi sudah ada
                    $cek = mysqli_query($koneksi,
                        "SELECT id_tu FROM t_transaksi_ujian 
                         WHERE semester = '$semester' 
                         AND id_panitia = '$id_panitia' 
                         AND id_user = '$id_user'"
                    );
                    
                    if (mysqli_num_rows($cek) > 0) {
                        // Jika opsi timpa aktif
                        if (isset($_POST['overwrite']) && $_POST['overwrite'] == '1') {
                            $update = mysqli_query($koneksi, "
                                UPDATE t_transaksi_ujian SET
                                    jml_mhs_prodi = '$jml_mhs_prodi',
                                    jml_mhs = '$jml_mhs',
                                    jml_koreksi = '$jml_koreksi',
                                    jml_matkul = '$jml_matkul',
                                    jml_pgws_pagi = '$jml_pgws_pagi',
                                    jml_pgws_sore = '$jml_pgws_sore',
                                    jml_koor_pagi = '$jml_koor_pagi',
                                    jml_koor_sore = '$jml_koor_sore'
                                WHERE semester = '$semester' 
                                AND id_panitia = '$id_panitia' 
                                AND id_user = '$id_user'
                            ");
                            
                            if ($update) $jumlahData++;
                            else $jumlahGagal++;
                        } else {
                            $errors[] = "Baris $i: Data untuk semester $semester sudah ada (gunakan opsi 'Timpa data')";
                            $jumlahGagal++;
                        }
                        continue;
                    }
                    
                    // Insert data baru
                    $insert = mysqli_query($koneksi, "
                        INSERT INTO t_transaksi_ujian 
                        (semester, id_panitia, id_user, jml_mhs_prodi, jml_mhs, jml_koreksi, jml_matkul, 
                         jml_pgws_pagi, jml_pgws_sore, jml_koor_pagi, jml_koor_sore)
                        VALUES
                        ('$semester', '$id_panitia', '$id_user', '$jml_mhs_prodi', '$jml_mhs', '$jml_koreksi', '$jml_matkul',
                         '$jml_pgws_pagi', '$jml_pgws_sore', '$jml_koor_pagi', '$jml_koor_sore')
                    ");
                    
                    if ($insert) {
                        $jumlahData++;
                    } else {
                        $errors[] = "Baris $i: Gagal menyimpan data";
                        $jumlahGagal++;
                    }
                }
                
                if ($jumlahData > 0) {
                    $success_message = "Berhasil mengimport <strong>$jumlahData</strong> data transaksi ujian.";
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

// Fetch data for dropdowns
$users = [];
$query = mysqli_query($koneksi, "SELECT id_user, npp_user, nama_user FROM t_user ORDER BY nama_user");
while ($row = mysqli_fetch_assoc($query)) {
    $users[$row['id_user']] = $row['npp_user'] . ' - ' . $row['nama_user'];
}

$panitia = [];
$query = mysqli_query($koneksi, "SELECT id_pnt, jbtn_pnt FROM t_panitia ORDER BY jbtn_pnt");
while ($row = mysqli_fetch_assoc($query)) {
    $panitia[$row['id_pnt']] = $row['jbtn_pnt'];
}
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Transaksi Ujian</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item">Upload Transaksi Ujian</div>
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
                                    <li>Kolom wajib: Semester, ID Panitia, ID User, dan jumlah-jumlah</li>
                                    <li>Semester harus dalam format: YYYYS (contoh: 20241)</li>
                                    <li>ID Panitia dan ID User harus valid</li>
                                    <li>Jumlah harus angka positif atau nol</li>
                                    <li>Data duplikat akan diabaikan</li>
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
                                        <i class="fas fa-download"></i> Download Template Transaksi Ujian.xlsx
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
                                            <small class="form-text text-muted">Jika dicentang, data dengan kombinasi yang sama akan ditimpa</small>
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
                                        <div class="form-group col-md-4">
                                            <label>Semester <span class="text-danger">*</span></label>
                                            <select class="form-control" name="manual_semester" required>
                                                <option value="">Pilih Semester</option>
                                                <option value="20241">2024 Ganjil (20241)</option>
                                                <option value="20242">2024 Genap (20242)</option>
                                                <option value="20251">2025 Ganjil (20251)</option>
                                                <option value="20252">2025 Genap (20252)</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Panitia <span class="text-danger">*</span></label>
                                            <select class="form-control" name="manual_panitia" required>
                                                <option value="">Pilih Panitia</option>
                                                <?php foreach ($panitia as $id => $nama): ?>
                                                <option value="<?= $id ?>"><?= htmlspecialchars($nama) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>User <span class="text-danger">*</span></label>
                                            <select class="form-control" name="manual_user" required>
                                                <option value="">Pilih User</option>
                                                <?php foreach ($users as $id => $nama): ?>
                                                <option value="<?= $id ?>"><?= htmlspecialchars($nama) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>Jml. Mhs Prodi</label>
                                            <input type="number" class="form-control" name="manual_jml_mhs_prodi" value="0" min="0">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Jml. Mahasiswa</label>
                                            <input type="number" class="form-control" name="manual_jml_mhs" value="0" min="0">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Jml. Koreksi</label>
                                            <input type="number" class="form-control" name="manual_jml_koreksi" value="0" min="0">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Jml. Mata Kuliah</label>
                                            <input type="number" class="form-control" name="manual_jml_matkul" value="0" min="0">
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>Pengawas Pagi</label>
                                            <input type="number" class="form-control" name="manual_jml_pgws_pagi" value="0" min="0">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Pengawas Sore</label>
                                            <input type="number" class="form-control" name="manual_jml_pgws_sore" value="0" min="0">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Koordinator Pagi</label>
                                            <input type="number" class="form-control" name="manual_jml_koor_pagi" value="0" min="0">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Koordinator Sore</label>
                                            <input type="number" class="form-control" name="manual_jml_koor_sore" value="0" min="0">
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
                                    $manual_semester = mysqli_real_escape_string($koneksi, $_POST['manual_semester']);
                                    $manual_panitia = mysqli_real_escape_string($koneksi, $_POST['manual_panitia']);
                                    $manual_user = mysqli_real_escape_string($koneksi, $_POST['manual_user']);
                                    $manual_jml_mhs_prodi = mysqli_real_escape_string($koneksi, $_POST['manual_jml_mhs_prodi']);
                                    $manual_jml_mhs = mysqli_real_escape_string($koneksi, $_POST['manual_jml_mhs']);
                                    $manual_jml_koreksi = mysqli_real_escape_string($koneksi, $_POST['manual_jml_koreksi']);
                                    $manual_jml_matkul = mysqli_real_escape_string($koneksi, $_POST['manual_jml_matkul']);
                                    $manual_jml_pgws_pagi = mysqli_real_escape_string($koneksi, $_POST['manual_jml_pgws_pagi']);
                                    $manual_jml_pgws_sore = mysqli_real_escape_string($koneksi, $_POST['manual_jml_pgws_sore']);
                                    $manual_jml_koor_pagi = mysqli_real_escape_string($koneksi, $_POST['manual_jml_koor_pagi']);
                                    $manual_jml_koor_sore = mysqli_real_escape_string($koneksi, $_POST['manual_jml_koor_sore']);
                                    
                                    // Validasi
                                    if (empty($manual_semester) || empty($manual_panitia) || empty($manual_user)) {
                                        echo '<div class="alert alert-danger">Semester, Panitia, dan User wajib diisi!</div>';
                                    } else {
                                        // Check if combination already exists
                                        $check = mysqli_query($koneksi, 
                                            "SELECT id_tu FROM t_transaksi_ujian 
                                             WHERE semester = '$manual_semester' 
                                             AND id_panitia = '$manual_panitia' 
                                             AND id_user = '$manual_user'"
                                        );
                                        
                                        if (mysqli_num_rows($check) > 0) {
                                            echo '<div class="alert alert-warning">Data untuk kombinasi ini sudah ada!</div>';
                                        } else {
                                            $insert_manual = mysqli_query($koneksi, "
                                                INSERT INTO t_transaksi_ujian 
                                                (semester, id_panitia, id_user, jml_mhs_prodi, jml_mhs, jml_koreksi, jml_matkul, 
                                                 jml_pgws_pagi, jml_pgws_sore, jml_koor_pagi, jml_koor_sore)
                                                VALUES
                                                ('$manual_semester', '$manual_panitia', '$manual_user', '$manual_jml_mhs_prodi', 
                                                 '$manual_jml_mhs', '$manual_jml_koreksi', '$manual_jml_matkul',
                                                 '$manual_jml_pgws_pagi', '$manual_jml_pgws_sore', 
                                                 '$manual_jml_koor_pagi', '$manual_jml_koor_sore')
                                            ");
                                            
                                            if ($insert_manual) {
                                                echo '<div class="alert alert-success">Data transaksi ujian berhasil disimpan!</div>';
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
                                <h5><i class="fas fa-table mr-2"></i>Data Transaksi Ujian Terbaru</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Semester</th>
                                                <th>Panitia</th>
                                                <th>User</th>
                                                <th>Jml. Mhs</th>
                                                <th>Jml. Matkul</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = mysqli_query($koneksi, 
                                                "SELECT tu.semester, p.jbtn_pnt, u.nama_user, 
                                                        tu.jml_mhs, tu.jml_matkul
                                                 FROM t_transaksi_ujian tu
                                                 LEFT JOIN t_panitia p ON tu.id_panitia = p.id_pnt
                                                 LEFT JOIN t_user u ON tu.id_user = u.id_user
                                                 ORDER BY tu.id_tu DESC 
                                                 LIMIT 10"
                                            );
                                            while ($row = mysqli_fetch_assoc($query)): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['semester']) ?></td>
                                                <td><?= htmlspecialchars($row['jbtn_pnt'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['nama_user'] ?? '-') ?></td>
                                                <td><?= $row['jml_mhs'] ?></td>
                                                <td><?= $row['jml_matkul'] ?></td>
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