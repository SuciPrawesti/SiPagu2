<?php
/**
 * UPLOAD HONOR DOSEN - SiPagu
 * Halaman untuk upload data honor dosen dari Excel
 * Lokasi: admin/upload_honor_dosen.php
 */

// Include required files
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config.php';

// Set page title
$page_title = "Upload Honor Dosen";

// Process form submission
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process Excel upload
    if (isset($_POST['submit'])) {
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
                    
                    // Debug: Tampilkan data yang dibaca dari Excel
                    $debug_data = [];
                    
                    // Mulai dari baris 1 (baris 0 = header)
                    for ($i = 1; $i < count($sheetData); $i++) {
                        $semester    = trim($sheetData[$i][0] ?? '');
                        $bulan       = trim($sheetData[$i][1] ?? '');
                        $kode_matkul = trim($sheetData[$i][2] ?? ''); // Kode Mata Kuliah
                        $jml_tm      = trim($sheetData[$i][3] ?? '0');
                        $sks_tempuh  = trim($sheetData[$i][4] ?? '0');
                        
                        // Simpan data untuk debugging
                        $debug_data[] = [
                            'baris' => $i + 1,
                            'semester' => $semester,
                            'bulan' => $bulan,
                            'kode_matkul' => $kode_matkul,
                            'jml_tm' => $jml_tm,
                            'sks_tempuh' => $sks_tempuh
                        ];
                        
                        // Skip baris kosong
                        if (empty($semester) && empty($bulan) && empty($kode_matkul)) {
                            continue; // Skip baris kosong total
                        }
                        
                        if (empty($semester) || empty($bulan) || empty($kode_matkul)) {
                            $errors[] = "Baris $i: Data tidak lengkap (Semester: '$semester', Bulan: '$bulan', Kode: '$kode_matkul')";
                            $jumlahGagal++;
                            continue;
                        }
                        
                        // Validasi semester format
                        if (!preg_match('/^\d{4}[12]$/', $semester)) {
                            $errors[] = "Baris $i: Format semester '$semester' tidak valid (contoh: 20241)";
                            $jumlahGagal++;
                            continue;
                        }
                        
                        // Validasi bulan
                        $bulan_list = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 
                                       'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
                        if (!in_array(strtolower($bulan), $bulan_list)) {
                            $errors[] = "Baris $i: Bulan '$bulan' tidak valid";
                            $jumlahGagal++;
                            continue;
                        }
                        
                        // Validasi angka
                        if (!is_numeric($jml_tm) || $jml_tm < 0) {
                            $errors[] = "Baris $i: Jumlah Tatap Muka harus angka positif";
                            $jumlahGagal++;
                            continue;
                        }
                        
                        if (!is_numeric($sks_tempuh) || $sks_tempuh < 0) {
                            $errors[] = "Baris $i: SKS Tempuh harus angka positif";
                            $jumlahGagal++;
                            continue;
                        }
                        
                        // Cari id_jadwal berdasarkan kode_matkul dan semester
                        $cek_jadwal = mysqli_query($koneksi, 
                            "SELECT j.id_jdwl, j.kode_matkul, j.nama_matkul, j.semester
                             FROM t_jadwal j
                             WHERE j.kode_matkul = '$kode_matkul' 
                             AND j.semester = '$semester' 
                             LIMIT 1"
                        );
                        
                        if (mysqli_num_rows($cek_jadwal) == 0) {
                            // Debug: Cek kode matkul apa saja yang ada
                            $cek_kode_lain = mysqli_query($koneksi, 
                                "SELECT kode_matkul, semester FROM t_jadwal 
                                 WHERE kode_matkul LIKE '%$kode_matkul%' 
                                 OR semester = '$semester'
                                 LIMIT 5"
                            );
                            
                            $kode_tersedia = [];
                            while ($row_kode = mysqli_fetch_assoc($cek_kode_lain)) {
                                $kode_tersedia[] = $row_kode['kode_matkul'] . " (semester: " . $row_kode['semester'] . ")";
                            }
                            
                            $kode_info = !empty($kode_tersedia) ? 
                                "Kode yang tersedia: " . implode(", ", $kode_tersedia) : 
                                "Tidak ada kode mata kuliah yang mirip";
                            
                            $errors[] = "Baris $i: Kode Mata Kuliah '$kode_matkul' tidak ditemukan untuk semester $semester. $kode_info";
                            $jumlahGagal++;
                            continue;
                        }
                        
                        $jadwal_data = mysqli_fetch_assoc($cek_jadwal);
                        $id_jadwal = $jadwal_data['id_jdwl'];
                        $nama_matkul = $jadwal_data['nama_matkul'];
                        
                        // Cek apakah kombinasi sudah ada
                        $cek = mysqli_query($koneksi,
                            "SELECT id_thd FROM t_transaksi_honor_dosen 
                             WHERE semester = '$semester' 
                             AND bulan = '$bulan' 
                             AND id_jadwal = '$id_jadwal'"
                        );
                        
                        if (mysqli_num_rows($cek) > 0) {
                            // Jika opsi timpa aktif
                            if (isset($_POST['overwrite']) && $_POST['overwrite'] == '1') {
                                $update = mysqli_query($koneksi, "
                                    UPDATE t_transaksi_honor_dosen SET
                                        jml_tm = '$jml_tm',
                                        sks_tempuh = '$sks_tempuh'
                                    WHERE semester = '$semester' 
                                    AND bulan = '$bulan' 
                                    AND id_jadwal = '$id_jadwal'
                                ");
                                
                                if ($update) {
                                    $jumlahData++;
                                } else {
                                    $errors[] = "Baris $i: Gagal mengupdate data $kode_matkul - " . mysqli_error($koneksi);
                                    $jumlahGagal++;
                                }
                            } else {
                                $errors[] = "Baris $i: Data untuk $kode_matkul ($nama_matkul) bulan $bulan semester $semester sudah ada";
                                $jumlahGagal++;
                            }
                            continue;
                        }
                        
                        // Insert data baru
                        $insert = mysqli_query($koneksi, "
                            INSERT INTO t_transaksi_honor_dosen 
                            (semester, bulan, id_jadwal, jml_tm, sks_tempuh)
                            VALUES
                            ('$semester', '$bulan', '$id_jadwal', '$jml_tm', '$sks_tempuh')
                        ");
                        
                        if ($insert) {
                            $jumlahData++;
                        } else {
                            $errors[] = "Baris $i: Gagal menyimpan data $kode_matkul - " . mysqli_error($koneksi);
                            $jumlahGagal++;
                        }
                    }
                    
                    // Tampilkan debug info jika dalam mode debug
                    if (isset($_GET['debug']) && !empty($debug_data)) {
                        echo "<div class='alert alert-info'>";
                        echo "<h5>Data yang dibaca dari Excel:</h5>";
                        echo "<table class='table table-sm'>";
                        echo "<tr><th>Baris</th><th>Semester</th><th>Bulan</th><th>Kode MK</th><th>Jml TM</th><th>SKS</th></tr>";
                        foreach ($debug_data as $debug) {
                            echo "<tr>";
                            echo "<td>{$debug['baris']}</td>";
                            echo "<td>{$debug['semester']}</td>";
                            echo "<td>{$debug['bulan']}</td>";
                            echo "<td><strong>{$debug['kode_matkul']}</strong></td>";
                            echo "<td>{$debug['jml_tm']}</td>";
                            echo "<td>{$debug['sks_tempuh']}</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        echo "</div>";
                    }
                    
                    if ($jumlahData > 0) {
                        $success_message = "Berhasil mengimport <strong>$jumlahData</strong> data honor dosen.";
                        if ($jumlahGagal > 0) {
                            $success_message .= " <strong>$jumlahGagal</strong> data gagal.";
                        }
                        if (!empty($errors)) {
                            $error_message = implode('<br>', array_slice($errors, 0, 10)); // Tampilkan 10 error pertama
                            if (count($errors) > 10) {
                                $error_message .= '<br>... dan ' . (count($errors) - 10) . ' error lainnya';
                            }
                        }
                    } else {
                        $error_message = "Tidak ada data yang berhasil diimport.";
                        if (!empty($errors)) {
                            $error_message = implode('<br>', array_slice($errors, 0, 10));
                            if (count($errors) > 10) {
                                $error_message .= '<br>... dan ' . (count($errors) - 10) . ' error lainnya';
                            }
                        }
                    }
                    
                } catch (Exception $e) {
                    $error_message = "Terjadi kesalahan saat membaca file: " . $e->getMessage();
                }
            }
        }
    }
    
    // Process manual input
    if (isset($_POST['submit_manual'])) {
        $manual_semester = mysqli_real_escape_string($koneksi, $_POST['manual_semester']);
        $manual_bulan = mysqli_real_escape_string($koneksi, $_POST['manual_bulan']);
        $manual_kode_matkul = mysqli_real_escape_string($koneksi, $_POST['manual_kode_matkul']);
        $manual_jml_tm = mysqli_real_escape_string($koneksi, $_POST['manual_jml_tm']);
        $manual_sks_tempuh = mysqli_real_escape_string($koneksi, $_POST['manual_sks_tempuh']);
        
        // Validasi
        if (empty($manual_semester) || empty($manual_bulan) || empty($manual_kode_matkul) || 
            empty($manual_jml_tm) || empty($manual_sks_tempuh)) {
            $error_message = "Semua field wajib diisi!";
        } else {
            // Check if jadwal exists
            $check_jadwal = mysqli_query($koneksi, 
                "SELECT j.id_jdwl, j.nama_matkul
                 FROM t_jadwal j
                 WHERE j.kode_matkul = '$manual_kode_matkul' 
                 AND j.semester = '$manual_semester' 
                 LIMIT 1"
            );
            
            if (mysqli_num_rows($check_jadwal) == 0) {
                $error_message = "Kode Mata Kuliah '$manual_kode_matkul' tidak ditemukan untuk semester $manual_semester!";
            } else {
                $jadwal_data = mysqli_fetch_assoc($check_jadwal);
                $id_jadwal = $jadwal_data['id_jdwl'];
                $nama_matkul = $jadwal_data['nama_matkul'];
                
                // Check if combination already exists
                $check = mysqli_query($koneksi, 
                    "SELECT id_thd FROM t_transaksi_honor_dosen 
                     WHERE semester = '$manual_semester' 
                     AND bulan = '$manual_bulan' 
                     AND id_jadwal = '$id_jadwal'"
                );
                
                if (mysqli_num_rows($check) > 0) {
                    $error_message = "Data untuk $manual_kode_matkul ($nama_matkul) bulan $manual_bulan semester $manual_semester sudah ada!";
                } else {
                    $insert_manual = mysqli_query($koneksi, "
                        INSERT INTO t_transaksi_honor_dosen 
                        (semester, bulan, id_jadwal, jml_tm, sks_tempuh)
                        VALUES
                        ('$manual_semester', '$manual_bulan', '$id_jadwal', 
                         '$manual_jml_tm', '$manual_sks_tempuh')
                    ");
                    
                    if ($insert_manual) {
                        $success_message = "Data honor dosen berhasil disimpan!";
                    } else {
                        $error_message = "Gagal menyimpan data: " . mysqli_error($koneksi);
                    }
                }
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

// Fetch data for dropdowns - Kode Mata Kuliah yang TERSEDIA
$kode_matkul_list = [];
$query = mysqli_query($koneksi, 
    "SELECT DISTINCT j.kode_matkul, j.nama_matkul, j.semester,
            (SELECT COUNT(*) FROM t_transaksi_honor_dosen th WHERE th.id_jadwal = j.id_jdwl) as jumlah_data
     FROM t_jadwal j
     ORDER BY j.semester DESC, j.kode_matkul"
);

$semester_20242_kodes = [];
$total_jadwal = 0;

while ($row = mysqli_fetch_assoc($query)) {
    $kode_matkul_list[$row['kode_matkul']] = $row['semester'] . ' - ' . $row['kode_matkul'] . ' - ' . 
                                             $row['nama_matkul'] . ' (' . $row['jumlah_data'] . ' data)';
    
    if ($row['semester'] == '20242') {
        $semester_20242_kodes[] = $row['kode_matkul'];
    }
    $total_jadwal++;
}

// Fetch data untuk preview
$preview_data = [];
$query = mysqli_query($koneksi, 
    "SELECT th.semester, th.bulan, th.jml_tm, th.sks_tempuh,
            j.kode_matkul, j.nama_matkul, u.nama_user
     FROM t_transaksi_honor_dosen th
     LEFT JOIN t_jadwal j ON th.id_jadwal = j.id_jdwl
     LEFT JOIN t_user u ON j.id_user = u.id_user
     ORDER BY th.id_thd DESC 
     LIMIT 10"
);
while ($row = mysqli_fetch_assoc($query)) {
    $preview_data[] = [
        'Semester' => $row['semester'],
        'Bulan' => ucfirst($row['bulan']),
        'Kode MK' => $row['kode_matkul'],
        'Mata Kuliah' => $row['nama_matkul'],
        'Dosen' => $row['nama_user'],
        'Jml. TM' => $row['jml_tm'],
        'SKS' => $row['sks_tempuh']
    ];
}
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Honor Dosen</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item">Upload Honor Dosen</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Debug Info Box -->
            <div class="alert alert-warning">
                <h6><i class="fas fa-exclamation-triangle mr-2"></i>Informasi Data Jadwal</h6>
                <p class="mb-1">Total data jadwal di sistem: <strong><?= $total_jadwal ?></strong></p>
                <p class="mb-1">Kode mata kuliah untuk semester 20242: 
                    <?php if (!empty($semester_20242_kodes)): ?>
                        <strong><?= implode(', ', $semester_20242_kodes) ?></strong>
                    <?php else: ?>
                        <span class="text-danger">Tidak ada data untuk semester 20242</span>
                    <?php endif; ?>
                </p>
                <small class="text-muted">Pastikan kode mata kuliah di Excel sudah terdaftar di sistem.</small>
            </div>
            
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
                            <div class="card-header-action">
                                <a href="check_jadwal.php" class="btn btn-info btn-sm">
                                    <i class="fas fa-search"></i> Cek Data Jadwal
                                </a>
                                <a href="upload_honor_dosen.php?debug=1" class="btn btn-warning btn-sm">
                                    <i class="fas fa-bug"></i> Mode Debug
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Instructions -->
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle mr-2"></i>Solusi untuk Error "Kode Mata Kuliah tidak ditemukan"</h6>
                                <ul class="mb-0 pl-3">
                                    <li>Pastikan kode mata kuliah sudah terdaftar di <a href="jadwal.php" target="_blank">Data Jadwal</a></li>
                                    <li>Pastikan semester di Excel sama dengan semester di Data Jadwal</li>
                                    <li>Untuk kode 'TI201', tambahkan dulu di halaman <a href="upload_jadwal.php">Upload Jadwal</a></li>
                                    <li>Gunakan kode yang tersedia: <?= !empty($semester_20242_kodes) ? '<strong>' . implode(', ', $semester_20242_kodes) . '</strong>' : 'Tidak ada data' ?></li>
                                </ul>
                            </div>

                            <!-- Quick Action Buttons -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <a href="upload_jadwal.php" class="btn btn-primary btn-block">
                                        <i class="fas fa-plus-circle"></i> Tambah Jadwal Baru
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="jadwal.php" class="btn btn-success btn-block">
                                        <i class="fas fa-list"></i> Lihat Semua Jadwal
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="templates/Template_Honor_Dosen.xlsx" class="btn btn-info btn-block">
                                        <i class="fas fa-download"></i> Download Template
                                    </a>
                                </div>
                            </div>

                            <!-- Upload Form -->
                            <form action="" method="POST" enctype="multipart/form-data" class="mt-4">
                                <div class="form-group">
                                    <label>Pilih File Excel <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="filexls" name="filexls" accept=".xls,.xlsx" required>
                                        <label class="custom-file-label" for="filexls">Pilih file...</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Format: .xls atau .xlsx (maks. 10MB). 
                                        <a href="#" data-toggle="modal" data-target="#formatModal">Lihat format yang benar</a>
                                    </small>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Filter Semester</label>
                                        <select class="form-control" name="semester">
                                            <option value="">Semua Semester</option>
                                            <option value="20241">2024 Ganjil (20241)</option>
                                            <option value="20242" selected>2024 Genap (20242)</option>
                                            <option value="20251">2025 Ganjil (20251)</option>
                                            <option value="20252">2025 Genap (20252)</option>
                                        </select>
                                        <small class="form-text text-muted">Filter data berdasarkan semester (opsional)</small>
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
                                    <button type="reset" class="btn btn-secondary">Reset Form</button>
                                </div>
                            </form>

                            <hr class="my-5">

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
                                                <option value="20242" selected>2024 Genap (20242)</option>
                                                <option value="20251">2025 Ganjil (20251)</option>
                                                <option value="20252">2025 Genap (20252)</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Bulan <span class="text-danger">*</span></label>
                                            <select class="form-control" name="manual_bulan" required>
                                                <option value="">Pilih Bulan</option>
                                                <option value="januari">Januari</option>
                                                <option value="februari">Februari</option>
                                                <option value="maret">Maret</option>
                                                <option value="april">April</option>
                                                <option value="mei">Mei</option>
                                                <option value="juni">Juni</option>
                                                <option value="juli">Juli</option>
                                                <option value="agustus">Agustus</option>
                                                <option value="september">September</option>
                                                <option value="oktober">Oktober</option>
                                                <option value="november">November</option>
                                                <option value="desember">Desember</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Kode Mata Kuliah <span class="text-danger">*</span></label>
                                            <select class="form-control" name="manual_kode_matkul" required>
                                                <option value="">Pilih Kode MK</option>
                                                <?php foreach ($kode_matkul_list as $kode => $nama): ?>
                                                <?php 
                                                // Highlight kode untuk semester 20242
                                                $is_20242 = strpos($nama, '20242') !== false;
                                                $style = $is_20242 ? 'font-weight: bold; color: #28a745;' : '';
                                                ?>
                                                <option value="<?= htmlspecialchars($kode) ?>" style="<?= $style ?>">
                                                    <?= htmlspecialchars($nama) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="form-text text-muted">
                                                Kode yang <strong style="color: #28a745;">hijau tebal</strong> tersedia untuk semester 20242
                                            </small>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Jumlah Tatap Muka <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="manual_jml_tm" placeholder="14" required min="0">
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>SKS Tempuh <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="manual_sks_tempuh" placeholder="3" required min="0" step="0.5">
                                            <small class="form-text text-muted">Contoh: 3, 2.5, 4</small>
                                        </div>
                                        <div class="form-group col-md-9">
                                            <div class="alert alert-light">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle"></i> 
                                                    Jika kode mata kuliah tidak ada dalam daftar, 
                                                    <a href="upload_jadwal.php">tambahkan dulu di Data Jadwal</a> 
                                                    sebelum menginput honor.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="submit" name="submit_manual" class="btn btn-success btn-icon icon-left">
                                            <i class="fas fa-save"></i> Simpan Data Manual
                                        </button>
                                        <button type="reset" class="btn btn-secondary">Reset Form</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Preview Data -->
                            <div class="mt-5">
                                <h5><i class="fas fa-table mr-2"></i>Data Honor Dosen Terbaru</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Semester</th>
                                                <th>Bulan</th>
                                                <th>Kode MK</th>
                                                <th>Mata Kuliah</th>
                                                <th>Dosen</th>
                                                <th>Jml. TM</th>
                                                <th>SKS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($preview_data)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">
                                                    <i class="fas fa-database fa-2x mb-2 d-block"></i>
                                                    Belum ada data honor dosen
                                                </td>
                                            </tr>
                                            <?php else: ?>
                                                <?php $no = 1; ?>
                                                <?php foreach ($preview_data as $row): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($row['Semester']) ?></td>
                                                    <td><?= htmlspecialchars($row['Bulan']) ?></td>
                                                    <td><strong><?= htmlspecialchars($row['Kode MK']) ?></strong></td>
                                                    <td><?= htmlspecialchars($row['Mata Kuliah']) ?></td>
                                                    <td><?= htmlspecialchars($row['Dosen']) ?></td>
                                                    <td><?= $row['Jml. TM'] ?></td>
                                                    <td><?= $row['SKS'] ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
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

<!-- Modal for Format Info -->
<div class="modal fade" id="formatModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Format File Excel yang Benar</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Kolom A</th>
                                <th>Kolom B</th>
                                <th>Kolom C</th>
                                <th>Kolom D</th>
                                <th>Kolom E</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Semester</strong></td>
                                <td><strong>Bulan</strong></td>
                                <td><strong>Kode Mata Kuliah</strong></td>
                                <td><strong>Jumlah Tatap Muka</strong></td>
                                <td><strong>SKS Tempuh</strong></td>
                            </tr>
                            <tr>
                                <td>20242</td>
                                <td>februari</td>
                                <td>SI101</td>
                                <td>14</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>20242</td>
                                <td>maret</td>
                                <td>SI101</td>
                                <td>12</td>
                                <td>3</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-info mt-3">
                    <h6>Kode Mata Kuliah yang tersedia:</h6>
                    <p class="mb-0">
                        <?php if (!empty($semester_20242_kodes)): ?>
                            <?= implode(', ', $semester_20242_kodes) ?>
                        <?php else: ?>
                            <span class="text-danger">Tidak ada kode mata kuliah untuk semester 20242</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Custom File Input -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Custom file input
    const fileInput = document.getElementById('filexls');
    const fileLabel = document.querySelector('.custom-file-label');
    
    if (fileInput && fileLabel) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileLabel.textContent = this.files[0].name;
                
                // Validasi ukuran file (10MB)
                if (this.files[0].size > 10 * 1024 * 1024) {
                    alert('File terlalu besar. Maksimal 10MB.');
                    this.value = '';
                    fileLabel.textContent = 'Pilih file...';
                }
                
                // Validasi ekstensi
                const allowedExt = ['xls', 'xlsx'];
                const fileName = this.files[0].name;
                const fileExt = fileName.split('.').pop().toLowerCase();
                
                if (!allowedExt.includes(fileExt)) {
                    alert('Format file harus .xls atau .xlsx');
                    this.value = '';
                    fileLabel.textContent = 'Pilih file...';
                }
            }
        });
    }
    
    // Auto select semester 20242 in filter
    const semesterFilter = document.querySelector('select[name="semester"]');
    if (semesterFilter) {
        semesterFilter.value = '20242';
    }
    
    // Alert auto dismiss
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                const closeBtn = alert.querySelector('.close');
                if (closeBtn) {
                    closeBtn.click();
                }
            }
        }, 8000); // Auto dismiss after 8 seconds
    });
});
</script>

<style>
.custom-file-label::after {
    content: "Browse";
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.075);
}

/* Style untuk kode 20242 di dropdown */
select option[style*="font-weight: bold"] {
    font-weight: bold !important;
    color: #28a745 !important;
}
</style>

<?php 
// Include footer
include __DIR__ . '/includes/footer.php';

// Include footer scripts
include __DIR__ . '/includes/footer_scripts.php';
?>
