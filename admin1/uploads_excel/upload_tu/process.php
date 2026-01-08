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
            
            // PROSES SETIAP BARIS DATA (MULAI DARI BARIS 1 UNTUK MELEWATI HEADER)
            for ($i = 1; $i < count($sheetData); $i++) {
                $semester         = trim($sheetData[$i][1] ?? '');
                $id_panitia       = trim($sheetData[$i][2] ?? '');
                $id_user          = trim($sheetData[$i][3] ?? '');
                $jml_mhs_prodi    = trim($sheetData[$i][4] ?? 0);
                $jml_mhs          = trim($sheetData[$i][5] ?? 0);
                $jml_koreksi      = trim($sheetData[$i][6] ?? 0);
                $jml_matkul       = trim($sheetData[$i][7] ?? 0);
                $jml_pgws_pagi    = trim($sheetData[$i][8] ?? 0);
                $jml_pgws_sore    = trim($sheetData[$i][9] ?? 0);
                $jml_koor_pagi    = trim($sheetData[$i][10] ?? 0);
                $jml_koor_sore    = trim($sheetData[$i][11] ?? 0);
                
                // VALIDASI DATA WAJIB
                if ($semester == '' || $id_panitia == '' || $id_user == '') {
                    continue;
                }
                
                // CEGAH DUPLIKAT BERDASARKAN SEMESTER, ID_PANITIA, DAN ID_USER
                $cek = mysqli_query($koneksi, "
                    SELECT id_tu FROM transaksi_ujian
                    WHERE semester='$semester'
                    AND id_panitia='$id_panitia'
                    AND id_user='$id_user'
                ");
                
                if (mysqli_num_rows($cek) > 0) {
                    $duplikatData++;
                    continue;
                }
                
                // INSERT DATA KE DATABASE
                $insert = mysqli_query($koneksi, "
                    INSERT INTO transaksi_ujian
                    (semester, id_panitia, id_user,
                     jml_mhs_prodi, jml_mhs, jml_koreksi, jml_matkul,
                     jml_pgws_pagi, jml_pgws_sore, jml_koor_pagi, jml_koor_sore)
                    VALUES
                    ('$semester', '$id_panitia', '$id_user',
                     '$jml_mhs_prodi', '$jml_mhs', '$jml_koreksi', '$jml_matkul',
                     '$jml_pgws_pagi', '$jml_pgws_sore', '$jml_koor_pagi', '$jml_koor_sore')
                ");
                
                if ($insert) {
                    $jumlahData++;
                }
            }
            
            // PESAN SUKSES
            $success = "<i class='fas fa-check-circle mr-2'></i> Berhasil mengimport <strong>$jumlahData</strong> data transaksi ujian baru.";
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