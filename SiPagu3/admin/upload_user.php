<?php
/**
 * UPLOAD DATA USER - SiPagu
 * Halaman untuk upload data user dari Excel
 * Lokasi: admin/upload_user.php
 */

// Include required files
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config.php';

// Set page title
$page_title = "Upload Data User";

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
                    $npp_user   = trim($sheetData[$i][0] ?? '');
                    $nik_user   = trim($sheetData[$i][1] ?? '');
                    $npwp_user  = trim($sheetData[$i][2] ?? '');
                    $norek_user = trim($sheetData[$i][3] ?? '');
                    $nama_user  = trim($sheetData[$i][4] ?? '');
                    $nohp_user  = trim($sheetData[$i][5] ?? '');
                    $role_user  = trim($sheetData[$i][6] ?? 'staff');
                    
                    // Skip baris kosong
                    if (empty($npp_user)) {
                        $jumlahGagal++;
                        continue;
                    }
                    
                    // Validasi format NPP
                    if (!preg_match('/^\d{4}\.\d{2}\.\d{4}\.\d{3}$/', $npp_user)) {
                        $errors[] = "Baris $i: Format NPP '$npp_user' tidak valid";
                        $jumlahGagal++;
                        continue;
                    }
                    
                    // Validasi NIK (16 digit)
                    if (!preg_match('/^\d{16}$/', $nik_user)) {
                        $errors[] = "Baris $i: NIK '$nik_user' harus 16 digit";
                        $jumlahGagal++;
                        continue;
                    }
                    
                    // Cek apakah NPP sudah ada
                    $cek = mysqli_query($koneksi,
                        "SELECT id_user FROM t_user WHERE npp_user = '$npp_user'"
                    );
                    
                    if (mysqli_num_rows($cek) > 0) {
                        // Jika opsi timpa aktif
                        if (isset($_POST['overwrite']) && $_POST['overwrite'] == '1') {
                            $pw_user = md5($npp_user);
                            
                            $update = mysqli_query($koneksi, "
                                UPDATE t_user SET
                                    nik_user = '$nik_user',
                                    npwp_user = '$npwp_user',
                                    norek_user = '$norek_user',
                                    nama_user = '$nama_user',
                                    nohp_user = '$nohp_user',
                                    role_user = '$role_user',
                                    pw_user = '$pw_user',
                                    honor_persks = 0
                                WHERE npp_user = '$npp_user'
                            ");
                            
                            if ($update) $jumlahData++;
                            else $jumlahGagal++;
                        } else {
                            $errors[] = "Baris $i: NPP '$npp_user' sudah ada (gunakan opsi 'Timpa data')";
                            $jumlahGagal++;
                        }
                        continue;
                    }
                    
                    // Insert data baru
                    $pw_user = md5($npp_user);
                    $honor_persks = 0;
                    
                    $insert = mysqli_query($koneksi, "
                        INSERT INTO t_user 
                        (npp_user, nik_user, npwp_user, norek_user, nama_user, nohp_user, pw_user, role_user, honor_persks)
                        VALUES
                        ('$npp_user', '$nik_user', '$npwp_user', '$norek_user', '$nama_user', '$nohp_user', '$pw_user', '$role_user', '$honor_persks')
                    ");
                    
                    if ($insert) {
                        $jumlahData++;
                    } else {
                        $errors[] = "Baris $i: Gagal menyimpan data '$npp_user'";
                        $jumlahGagal++;
                    }
                }
                
                if ($jumlahData > 0) {
                    $success_message = "Berhasil mengimport <strong>$jumlahData</strong> data user.";
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

// Fetch user data for dropdown (for manual input)
$users = [];
$query = mysqli_query($koneksi, "SELECT id_user, npp_user, nama_user FROM t_user ORDER BY nama_user");
while ($row = mysqli_fetch_assoc($query)) {
    $users[$row['id_user']] = $row['npp_user'] . ' - ' . $row['nama_user'];
}
?>


<?php include __DIR__ . '/includes/header.php'; ?>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<?php include __DIR__ . '/includes/sidebar_admin.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Data User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item">Upload Data User</div>
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
                                    <li>Kolom wajib: NPP, NIK, NPWP, Nomor Rekening, Nama, No. HP, Role</li>
                                    <li>Format NPP: XXXX.XX.XXXX.XXX (contoh: 0686.11.1995.071)</li>
                                    <li>NIK harus 16 digit angka</li>
                                    <li>Password default akan digenerate dari NPP</li>
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
                                        <i class="fas fa-download"></i> Download Template User.xlsx
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
                                            <small class="form-text text-muted">Jika dicentang, data dengan NPP yang sama akan ditimpa</small>
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
                                            <label>NPP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="manual_npp" placeholder="0686.11.1995.071" required>
                                            <small class="form-text text-muted">Format: XXXX.XX.XXXX.XXX</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>NIK <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="manual_nik" placeholder="3374010101950001" maxlength="16" required>
                                            <small class="form-text text-muted">16 digit Nomor Induk Kependudukan</small>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>NPWP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="manual_npwp" placeholder="12.345.678.9-012.000" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Nomor Rekening <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="manual_norek" placeholder="1410001234567" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="manual_nama" placeholder="Dr. Andi Prasetyo, M.Kom" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Nomor HP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="manual_nohp" placeholder="081234567890" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Role <span class="text-danger">*</span></label>
                                            <select class="form-control" name="manual_role" required>
                                                <option value="">Pilih Role</option>
                                                <option value="admin">Admin</option>
                                                <option value="koordinator">Koordinator</option>
                                                <option value="staff">Staff</option>
                                            </select>
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
                                    $manual_npp = mysqli_real_escape_string($koneksi, $_POST['manual_npp']);
                                    $manual_nik = mysqli_real_escape_string($koneksi, $_POST['manual_nik']);
                                    $manual_npwp = mysqli_real_escape_string($koneksi, $_POST['manual_npwp']);
                                    $manual_norek = mysqli_real_escape_string($koneksi, $_POST['manual_norek']);
                                    $manual_nama = mysqli_real_escape_string($koneksi, $_POST['manual_nama']);
                                    $manual_nohp = mysqli_real_escape_string($koneksi, $_POST['manual_nohp']);
                                    $manual_role = mysqli_real_escape_string($koneksi, $_POST['manual_role']);
                                    
                                    // Validate NPP format
                                    if (!preg_match('/^\d{4}\.\d{2}\.\d{4}\.\d{3}$/', $manual_npp)) {
                                        echo '<div class="alert alert-danger">Format NPP tidak valid!</div>';
                                    } 
                                    // Validate NIK
                                    elseif (!preg_match('/^\d{16}$/', $manual_nik)) {
                                        echo '<div class="alert alert-danger">NIK harus 16 digit angka!</div>';
                                    }
                                    else {
                                        // Check if NPP already exists
                                        $check = mysqli_query($koneksi, "SELECT id_user FROM t_user WHERE npp_user = '$manual_npp'");
                                        
                                        if (mysqli_num_rows($check) > 0) {
                                            echo '<div class="alert alert-warning">NPP sudah terdaftar!</div>';
                                        } else {
                                            $manual_pw = md5($manual_npp);
                                            $manual_honor = 0;
                                            
                                            $insert_manual = mysqli_query($koneksi, "
                                                INSERT INTO t_user 
                                                (npp_user, nik_user, npwp_user, norek_user, nama_user, nohp_user, pw_user, role_user, honor_persks)
                                                VALUES
                                                ('$manual_npp', '$manual_nik', '$manual_npwp', '$manual_norek', '$manual_nama', '$manual_nohp', '$manual_pw', '$manual_role', '$manual_honor')
                                            ");
                                            
                                            if ($insert_manual) {
                                                echo '<div class="alert alert-success">Data user berhasil disimpan!</div>';
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
                                <h5><i class="fas fa-table mr-2"></i>Data User Terbaru</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>NPP</th>
                                                <th>Nama</th>
                                                <th>Role</th>
                                                <th>No. HP</th>
                                                <th>Tanggal Input</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = mysqli_query($koneksi, 
                                                "SELECT npp_user, nama_user, role_user, nohp_user, 
                                                        DATE_FORMAT(NOW(), '%d/%m/%Y') as tanggal 
                                                 FROM t_user 
                                                 ORDER BY id_user DESC 
                                                 LIMIT 10"
                                            );
                                            while ($row = mysqli_fetch_assoc($query)): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['npp_user']) ?></td>
                                                <td><?= htmlspecialchars($row['nama_user']) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= 
                                                        $row['role_user'] == 'admin' ? 'primary' : 
                                                        ($row['role_user'] == 'koordinator' ? 'warning' : 'secondary')
                                                    ?>">
                                                        <?= ucfirst($row['role_user']) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($row['nohp_user']) ?></td>
                                                <td><?= $row['tanggal'] ?></td>
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