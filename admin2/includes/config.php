<?php
/**
 * =========================================
 * CONFIGURATION FILE - SiPagu
 * Lokasi: admin/includes/config.php
 * =========================================
 */

/* ================= DATABASE ================= */
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'db_sistem_honor_udinus';

$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

/* ================= TIMEZONE ================= */
date_default_timezone_set('Asia/Jakarta');

/* ================= SESSION ================= */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ================= BASE URL =================
 * HARUS mengarah ke ROOT PROJECT
 * Contoh struktur:
 * htdocs/SiPagu/
 *   ├── assets
 *   ├── admin
 *   ├── index.php
 */
define('BASE_URL', 'http://localhost/SiPagu/admin/');

/* ================= PATH ABSOLUTE (SERVER) ================= */
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/SiPagu/');
define('ADMIN_PATH', ROOT_PATH . 'admin/');
define('ASSETS_PATH', ROOT_PATH . 'assets/');

/* ================= SECURITY BASIC ================= */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ================= HELPER ================= */
/**
 * Redirect helper
 */
function redirect($url)
{
    header("Location: " . BASE_URL . $url);
    exit;
}


