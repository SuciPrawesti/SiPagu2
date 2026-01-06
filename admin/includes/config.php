<?php
// Koneksi Database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'db_sistem_honor_udinus';

$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Start session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fungsi untuk menampilkan pesan alert
function showAlert($type, $message) {
    return '<div class="alert alert-' . $type . ' alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    ' . $message . '
                </div>
            </div>';
}

// Fungsi untuk redirect dengan pesan
function redirectWithMessage($url, $type, $message) {
    $_SESSION['upload_message'] = showAlert($type, $message);
    header('Location: ' . $url);
    exit();
}
?>