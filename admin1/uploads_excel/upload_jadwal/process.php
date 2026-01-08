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
                $jbtn_pnt  = trim($sheetData[$i][1] ?? '');
                $honor_std = trim($sheetData[$i][2] ?? 0);
                $honor_p1  = trim($sheetData[$i][3] ?? 0);
                $honor_p2  = trim($sheetData[$i][4] ?? 0);
                
                // VALIDASI DATA WAJIB
                if ($jbtn_pnt == '' || $honor_p1 == '' || $honor_p2 == '') {
                    continue;
                }
                
                // CEK DUPLIKAT (BERDASARKAN JABATAN)
                $cek = mysqli_query($koneksi, "
                    SELECT id_pnt 
                    FROM t_pnt 
                    WHERE jbtn_pnt='$jbtn_pnt'
                ");
                
                if (mysqli_num_rows($cek) > 0) {
                    $duplikatData++;
                    continue;
                }
                
                // INSERT DATA KE DATABASE
                $insert = mysqli_query($koneksi, "
                    INSERT INTO t_pnt
                    (jbtn_pnt, honor_std, honor_p1, honor_p2)
                    VALUES
                    ('$jbtn_pnt', '$honor_std', '$honor_p1', '$honor_p2')
                ");
                
                if ($insert) {
                    $jumlahData++;
                }
            }
            
            // PESAN SUKSES
            $success = "<i class='fas fa-check-circle mr-2'></i> Berhasil mengimport <strong>$jumlahData</strong> data jadwal lainnya baru.";
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