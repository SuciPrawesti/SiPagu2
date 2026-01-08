<?php
// Debug penanda
echo '<!-- FOOTER SCRIPT TERLOAD -->';

// Pastikan BASE_URL konsisten
// DISARANKAN: define('BASE_URL', '/SiPagu/');
?>

<!-- ================= GENERAL JS ================= -->
<script src="<?= BASE_URL ?>assets/modules/jquery.min.js"></script>
<script src="<?= BASE_URL ?>assets/modules/popper.js"></script>
<script src="<?= BASE_URL ?>assets/modules/tooltip.js"></script>
<script src="<?= BASE_URL ?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= BASE_URL ?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
<script src="<?= BASE_URL ?>assets/modules/moment.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/stisla.js"></script>

<!-- ================= LIBRARIES ================= -->
<script src="<?= BASE_URL ?>assets/modules/jquery.sparkline.min.js"></script>
<script src="<?= BASE_URL ?>assets/modules/chart.min.js"></script>
<script src="<?= BASE_URL ?>assets/modules/owlcarousel2/dist/owl.carousel.min.js"></script>
<script src="<?= BASE_URL ?>assets/modules/summernote/summernote-bs4.js"></script>
<script src="<?= BASE_URL ?>assets/modules/chocolat/dist/js/jquery.chocolat.min.js"></script>

<!-- ================= TEMPLATE JS ================= -->
<script src="<?= BASE_URL ?>assets/js/scripts.js"></script>
<script src="<?= BASE_URL ?>assets/js/custom.js"></script>

<?php
/**
 * ================= PAGE SPECIFIC JS =================
 * HANYA load jika:
 * 1. File JS ada
 * 2. Halaman BUKAN halaman upload
 */

// Ambil nama file PHP
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Halaman yang TIDAK BOLEH load JS dashboard
$blocked_pages = [
    'upload',
    'process',
    'form_upload',
];

// Jika bukan halaman terblokir
if (!in_array($current_page, $blocked_pages)) {

    $page_js_url  = BASE_URL . 'assets/js/page/' . $current_page . '.js';
    $page_js_real = $_SERVER['DOCUMENT_ROOT'] . '/SiPagu/assets/js/page/' . $current_page . '.js';

    if (file_exists($page_js_real)) {
        echo '<script src="' . $page_js_url . '"></script>';
    }
}
?>
