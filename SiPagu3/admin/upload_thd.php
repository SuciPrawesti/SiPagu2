<?php
/**
 * UPLOAD JADWAL DOSEN - SiPagu
 * Halaman untuk upload data jadwal dosen dari Excel
 * Lokasi: admin/upload_thd.php
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config.php';

$page_title = "Upload Jadwal Dosen";

$error_message = '';
$success_message = '';

// Fetch data for dropdowns
$users = [];
$query = mysqli_query($koneksi, "SELECT id_user, npp_user, nama_user FROM t_user ORDER BY nama_user");
while ($row = mysqli_fetch_assoc($query)) {
    $users[$row['id_user']] = $row['npp_user'] . ' - ' . $row['nama_user'];
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_excel'])) {
        // Upload dari Excel
        if (empty($_FILES['filexls']['name'])) {
            $error_message = 'Silakan pilih file Excel.';
        } else {
            $file_name = $_FILES['filexls']['name'];
            $file_tmp = $_FILES['filexls']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            $allowed_ext = ['xls', 'xlsx'];
            if (!in_array($file_ext, $allowed_ext)) {
                $error_message = 'File harus bertipe XLS atau XLSX.';
            } else {
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
                        
                        // Cek apakah kombinasi sudah ada
                        $cek = mysqli_query($koneksi,
                            "SELECT id_jdwl FROM t_jadwal 
                             WHERE semester = '$semester' 
                             AND kode_matkul = '$kode_matkul' 
                             AND id_user = '$id_user'"
                        );
                        
                        if (mysqli_num_rows($cek) > 0) {
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
                    }
                    
                } catch (Exception $e) {
                    $error_message = "Terjadi kesalahan saat membaca file: " . $e->getMessage();
                }
            }
        }
    }
    
    // Manual input
    if (isset($_POST['submit_manual'])) {
        $manual_semester = mysqli_real_escape_string($koneksi, $_POST['manual_semester']);
        $manual_kode_matkul = mysqli_real_escape_string($koneksi, $_POST['manual_kode_matkul']);
        $manual_nama_matkul = mysqli_real_escape_string($koneksi, $_POST['manual_nama_matkul']);
        $manual_user = mysqli_real_escape_string($koneksi, $_POST['manual_user']);
        $manual_jml_mhs = mysqli_real_escape_string($koneksi, $_POST['manual_jml_mhs']);
        
        if (empty($manual_semester) || empty($manual_kode_matkul) || 
            empty($manual_nama_matkul) || empty($manual_user)) {
            $error_message = "Semua field wajib diisi!";
        } else {
            // Check if data already exists
            $check = mysqli_query($koneksi, 
                "SELECT id_jdwl FROM t_jadwal 
                 WHERE semester = '$manual_semester' 
                 AND kode_matkul = '$manual_kode_matkul' 
                 AND id_user = '$manual_user'"
            );
            
            if (mysqli_num_rows($check) > 0) {
                $error_message = "Data untuk kode mata kuliah ini sudah ada!";
            } else {
                $insert_manual = mysqli_query($koneksi, "
                    INSERT INTO t_jadwal 
                    (semester, kode_matkul, nama_matkul, id_user, jml_mhs)
                    VALUES
                    ('$manual_semester', '$manual_kode_matkul', '$manual_nama_matkul', 
                     '$manual_user', '$manual_jml_mhs')
                ");
                
                if ($insert_manual) {
                    $success_message = "Data jadwal berhasil disimpan!";
                } else {
                    $error_message = "Gagal menyimpan data: " . mysqli_error($koneksi);
                }
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
include __DIR__ . '/includes/sidebar_admin.php';
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Jadwal Dosen</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item">Upload Jadwal Dosen</div>
            </div>
        </div>

        <div class="section-body">
            <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>×</span></button>
                    <i class="fas fa-exclamation-circle mr-2"></i><?= $error_message ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>×</span></button>
                    <i class="fas fa-check-circle mr-2"></i><?= $success_message ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Upload Jadwal Dosen dari Excel</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle mr-2"></i>Petunjuk</h6>
                                <p>Upload data jadwal mengajar dosen. Format kolom:</p>
                                <ol class="mb-0 pl-3">
                                    <li>Semester (contoh: 20241)</li>
                                    <li>Kode Mata Kuliah</li>
                                    <li>Nama Mata Kuliah</li>
                                    <li>ID User/Dosen</li>
                                    <li>Jumlah Mahasiswa</li>
                                </ol>
                            </div>

                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Pilih File Excel</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="filexls" name="filexls" accept=".xls,.xlsx">
                                        <label class="custom-file-label" for="filexls">Pilih file...</label>
                                    </div>
                                </div>

                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="overwrite" name="overwrite" value="1">
                                    <label class="custom-control-label" for="overwrite">Timpa data yang sudah ada</label>
                                </div>

                                <button type="submit" name="submit_excel" class="btn btn-primary">
                                    <i class="fas fa-upload mr-2"></i>Upload Excel
                                </button>
                            </form>

                            <hr class="my-5">

                            <h5>Input Manual Jadwal Dosen</h5>
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
                                    <div class="form-group col-md-2">
                                        <label>Kode MK <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="manual_kode_matkul" placeholder="SI101" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Nama MK <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="manual_nama_matkul" placeholder="Algoritma" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Dosen <span class="text-danger">*</span></label>
                                        <select class="form-control" name="manual_user" required>
                                            <option value="">Pilih Dosen</option>
                                            <?php foreach ($users as $id => $nama): ?>
                                            <option value="<?= $id ?>"><?= htmlspecialchars($nama) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Jumlah MHS</label>
                                        <input type="number" class="form-control" name="manual_jml_mhs" value="0" min="0">
                                    </div>
                                </div>

                                <button type="submit" name="submit_manual" class="btn btn-success">
                                    <i class="fas fa-save mr-2"></i>Simpan Manual
                                </button>
                            </form>

                            <div class="mt-5">
                                <h5>Data Jadwal Terbaru</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Semester</th>
                                                <th>Kode MK</th>
                                                <th>Mata Kuliah</th>
                                                <th>Dosen</th>
                                                <th>Jml MHS</th>
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

<?php 
include __DIR__ . '/includes/footer.php';
include __DIR__ . '/includes/footer_scripts.php';
?>