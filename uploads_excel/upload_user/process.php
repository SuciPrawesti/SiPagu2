<?php
// INCLUDE KONEKSI DATABASE
require_once '../../includes/config.php';

// CEK APAKAH FORM SUDAH DISUBMIT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    
    // INCLUDE PHPSPREADSHEET
    require_once '../../../vendor/autoload.php';
    
    $err = "";
    $success = "";
    
    $file_name = $_FILES['filexls']['name'];
    $file_tmp  = $_FILES['filexls']['tmp_name'];
    
    // VALIDASI FILE
    if (empty($file_name)) {
        $err .= "Silakan pilih file Excel.";
    } else {
        $ekstensi = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!in_array($ekstensi, ['xls','xlsx'])) {
            $err .= "File harus bertipe XLS atau XLSX.";
        }
    }
    
    if (empty($err)) {
        try {
            // LOAD FILE EXCEL
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_tmp);
            $spreadsheet = $reader->load($file_tmp);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            
            $jumlahData = 0;
            $duplikatData = 0;
            
            // PROSES SETIAP BARIS DATA
            for ($i = 0; $i < count($sheetData); $i++) {
                // Skip baris header jika ada
                if ($i == 0 && !is_numeric(trim($sheetData[$i][1]))) {
                    continue;
                }
                
                $npp_user   = trim($sheetData[$i][1] ?? '');
                $nik_user   = trim($sheetData[$i][2] ?? '');
                $npwp_user  = trim($sheetData[$i][3] ?? '');
                $norek_user = trim($sheetData[$i][4] ?? '');
                $nama_user  = trim($sheetData[$i][5] ?? '');
                $nohp_user  = trim($sheetData[$i][6] ?? '');
                
                // VALIDASI NPP
                if ($npp_user == '' || !is_numeric($npp_user)) {
                    continue;
                }
                
                // CEK DUPLIKAT NPP
                $cek = mysqli_query($koneksi,
                    "SELECT id_user FROM t_user WHERE npp_user='$npp_user'"
                );
                
                if (mysqli_num_rows($cek) > 0) {
                    $duplikatData++;
                    continue;
                }
                
                // SET DEFAULT VALUE
                $role_user = 'staff';
                $pw_user = md5($npp_user);
                $honor_persks = 0;
                
                // INSERT DATA KE DATABASE
                $insert = mysqli_query($koneksi, "
                    INSERT INTO t_user
                    (npp_user, nik_user, npwp_user, norek_user, nama_user, nohp_user, pw_user, role_user, honor_persks)
                    VALUES
                    ('$npp_user','$nik_user','$npwp_user','$norek_user','$nama_user','$nohp_user','$pw_user','$role_user','$honor_persks')
                ");
                
                if ($insert) {
                    $jumlahData++;
                }
            }
            
            // PESAN SUKSES
            $success = "<i class='fas fa-check-circle mr-2'></i> Berhasil mengimport <strong>$jumlahData</strong> data user baru.";
            if ($duplikatData > 0) {
                $success .= " <strong>$duplikatData</strong> data duplikat dilewati.";
            }
            
            // SIMPAN PESAN KE SESSION
            $_SESSION['upload_message'] = showAlert('success', $success);
            
        } catch (Exception $e) {
            $_SESSION['upload_message'] = showAlert('danger', 
                "<i class='fas fa-exclamation-triangle mr-2'></i> Error: " . $e->getMessage()
            );
        }
        
    } else {
        // SIMPAN PESAN ERROR KE SESSION
        $_SESSION['upload_message'] = showAlert('danger', 
            "<i class='fas fa-exclamation-triangle mr-2'></i> $err"
        );
    }
    
    // REDIRECT KEMBALI KE HALAMAN UPLOAD
    header('Location: index.php');
    exit();
    
} else {
    // JIKA AKSES LANGSUNG, REDIRECT KE INDEX
    header('Location: index.php');
    exit();
}
?>