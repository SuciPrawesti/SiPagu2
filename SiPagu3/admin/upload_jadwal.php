<?php
/**
 * UPLOAD JADWAL LAIN - SiPagu
 * Halaman untuk upload jadwal lainnya dari Excel
 * Lokasi: admin/upload_jadwal.php
 */

// Include required files
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config.php';

// Set page title
$page_title = "Upload Jadwal Lain";

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
                    $semester      = trim($sheetData[$i][0] ?? '');
                    $kode_matkul   = trim($sheetData[$i][1] ?? '');
                    $nama_matkul   = trim($sheetData[$i][2] ?? '');
                    $id_user       = trim($sheetData[$i][3] ?? '');
                    $jml_mhs       = trim($sheetData[$i][4] ?? '0');
                    
                    // Skip baris kosong
                    if (empty($semester) || empty($kode_matkul) || empty($nama_matkul) || empty($id_user)) {
                        $jumlahGagal++;
                        continue;
                    }
                    
                    // Validasi semester format
                    if (!preg_match('/^\d{4}[12]$/', $semester)) {
                        $errors[] = "Baris $i: Format semester '$semester' tidak valid";
                        $jumlahGagal++;
                        continue;
                    }
                    
                    // Validasi angka
                    if (!is_numeric($jml_mhs) || $jml_mhs < 0) {
                        $errors[] = "Baris $i: Jumlah Mahasiswa harus angka positif";
                        $jumlahGagal++;
                        continue;
                    }
                    
                    // Cek apakah kombinasi sudah ada
                    $cek = mysqli_query($koneksi,
                        "SELECT id_jdwl FROM t_jadwal 
                         WHERE semester = '$semester' 
                         AND kode_matkul = '$kode_matkul' 
                         AND id_user = '$id_user'"
                    );
                    
                    if (mysqli_num_rows($cek) > 0) {
                        // Jika opsi timpa aktif
                        if (isset($_POST['overwrite']) && $_POST['overwrite'] == '1') {
                            $update = mysqli_query($koneksi, "
                                UPDATE t_jadwal SET
                                    nama_matkul = '$nama_matkul',
                                    jml_mhs = '$jml_mhs'
                                WHERE semester = '$semester' 
                                AND kode_matkul = '$kode_matkul' 
                                AND id_user = '$id_user'
                            ");
                            
                            if ($update) $jumlahData++;
                            else $jumlahGagal++;
                        } else {
                            $errors[] = "Baris $i: Data untuk kode $kode_matkul sudah ada";
                            $jumlahGagal++;
                        }
                        continue;
                    }
                    
                    // Insert data baru
                    $insert = mysqli_query($koneksi, "
                        INSERT INTO t_jadwal 
                        (semester, kode_matkul, nama_matkul, id_user, jml_mhs)
                        VALUES
                        ('$semester', '$kode_matkul', '$nama_matkul', '$id_user', '$jml_mhs')
                    ");
                    
                    if ($insert) {
                        $jumlahData++;
                    } else {
                        $errors[] = "Baris $i: Gagal menyimpan data";
                        $jumlahGagal++;
                    }
                }
                
                if ($jumlahData > 0) {
                    $success_message = "Berhasil mengimport <strong>$jumlahData</strong> data jadwal.";
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
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Jadwal Lain</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item">Upload Jadwal Lain</div>
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
                                    <li>Kolom wajib: Semester, Kode Mata Kuliah, Nama Mata Kuliah, ID User, Jumlah Mahasiswa</li>
                                    <li>Kode mata kuliah harus unik untuk semester yang sama</li>
                                    <li>ID User harus valid dari tabel user</li>
                                    <li>Jumlah mahasiswa harus angka positif atau nol</li>
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
                                        <i class="fas fa-download"></i> Download Template Jadwal Lain.xlsx
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
                                        <div class="form-group col-md-3">
                                            <label>Semester <span class="text-danger">*</span></label>
                                            <select class="form-control" name="manual_semester" required>
                                                <option value="">Pilih Semester</option>
                                                <option value="20241">2024 Ganjil (20241)</option>
                                                <option value="20242">2024 Genap (20242)</option>
                                                <option value="20251">2025 Ganjil (20251)</option>
                                                <option value="20252">2025 Genap (20252)</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Kode Mata Kuliah <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="manual_kode_matkul" placeholder="SI101" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Nama Mata Kuliah <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="manual_nama_matkul" placeholder="Algoritma dan Pemrograman" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Dosen Pengampu <span class="text-danger">*</span></label>
                                            <select class="form-control" name="manual_user" required>
                                                <option value="">Pilih Dosen</option>
                                                <?php foreach ($users as $id => $nama): ?>
                                                <option value="<?= $id ?>"><?= htmlspecialchars($nama) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Jumlah Mahasiswa</label>
                                            <input type="number" class="form-control" name="manual_jml_mhs" value="0" min="0">
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
                                    $manual_kode_matkul = mysqli_real_escape_string($koneksi, $_POST['manual_kode_matkul']);
                                    $manual_nama_matkul = mysqli_real_escape_string($koneksi, $_POST['manual_nama_matkul']);
                                    $manual_user = mysqli_real_escape_string($koneksi, $_POST['manual_user']);
                                    $manual_jml_mhs = mysqli_real_escape_string($koneksi, $_POST['manual_jml_mhs']);
                                    
                                    // Validasi
                                    if (empty($manual_semester) || empty($manual_kode_matkul) || 
                                        empty($manual_nama_matkul) || empty($manual_user)) {
                                        echo '<div class="alert alert-danger">Semua field wajib diisi!</div>';
                                    } else {
                                        // Check if data already exists
                                        $check = mysqli_query($koneksi, 
                                            "SELECT id_jdwl FROM t_jadwal 
                                             WHERE semester = '$manual_semester' 
                                             AND kode_matkul = '$manual_kode_matkul' 
                                             AND id_user = '$manual_user'"
                                        );
                                        
                                        if (mysqli_num_rows($check) > 0) {
                                            echo '<div class="alert alert-warning">Data untuk kode mata kuliah ini sudah ada!</div>';
                                        } else {
                                            $insert_manual = mysqli_query($koneksi, "
                                                INSERT INTO t_jadwal 
                                                (semester, kode_matkul, nama_matkul, id_user, jml_mhs)
                                                VALUES
                                                ('$manual_semester', '$manual_kode_matkul', '$manual_nama_matkul', 
                                                 '$manual_user', '$manual_jml_mhs')
                                            ");
                                            
                                            if ($insert_manual) {
                                                echo '<div class="alert alert-success">Data jadwal berhasil disimpan!</div>';
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
                                <h5><i class="fas fa-table mr-2"></i>Data Jadwal Terbaru</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Semester</th>
                                                <th>Kode</th>
                                                <th>Mata Kuliah</th>
                                                <th>Dosen</th>
                                                <th>Jml. Mhs</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = mysqli_query($koneksi, 
                                                "SELECT j.semester, j.kode_matkul, j.nama_matkul, 
                                                        u.nama_user, j.jml_mhs
                                                 FROM t_jadwal j
                                                 LEFT JOIN t_user u ON j.id_user = u.id_user
                                                 ORDER BY j.id_jdwl DESC 
                                                 LIMIT 10"
                                            );
                                            while ($row = mysqli_fetch_assoc($query)): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['semester']) ?></td>
                                                <td><strong><?= htmlspecialchars($row['kode_matkul']) ?></strong></td>
                                                <td><?= htmlspecialchars($row['nama_matkul']) ?></td>
                                                <td><?= htmlspecialchars($row['nama_user'] ?? '-') ?></td>
                                                <td><?= $row['jml_mhs'] ?></td>
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