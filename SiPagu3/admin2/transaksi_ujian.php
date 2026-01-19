<?php
/**
 * DATA TRANSAKSI UJIAN - SiPagu
 * Halaman untuk melihat, mengedit, menghapus data transaksi ujian
 * Lokasi: admin/transaksi_ujian.php
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config.php';

$page_title = "Data Transaksi Ujian";

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $delete = mysqli_query($koneksi, "DELETE FROM t_transaksi_ujian WHERE id_tu = '$id'");
    
    if ($delete) {
        $_SESSION['success_message'] = "Data transaksi ujian berhasil dihapus!";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus data: " . mysqli_error($koneksi);
    }
    header("Location: transaksi_ujian.php");
    exit;
}

// Ambil data
$query = mysqli_query($koneksi, "
    SELECT tu.*, u.nama_user, p.jbtn_pnt 
    FROM t_transaksi_ujian tu
    LEFT JOIN t_user u ON tu.id_user = u.id_user
    LEFT JOIN t_panitia p ON tu.id_panitia = p.id_pnt
    ORDER BY tu.id_tu DESC
");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
include __DIR__ . '/includes/sidebar_admin.php';
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Transaksi Ujian</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item">Master Data</div>
                <div class="breadcrumb-item">Data Transaksi Ujian</div>
            </div>
        </div>

        <div class="section-body">
            <?php 
            if (isset($_SESSION['success_message'])): 
                echo '<div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>×</span></button>
                        <i class="fas fa-check-circle mr-2"></i>' . $_SESSION['success_message'] . '
                    </div>
                </div>';
                unset($_SESSION['success_message']);
            endif;
            
            if (isset($_SESSION['error_message'])): 
                echo '<div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>×</span></button>
                        <i class="fas fa-exclamation-circle mr-2"></i>' . $_SESSION['error_message'] . '
                    </div>
                </div>';
                unset($_SESSION['error_message']);
            endif;
            ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Transaksi Ujian</h4>
                            <div class="card-header-action">
                                <a href="<?= BASE_URL ?>admin/upload_tu.php" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Data
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Semester</th>
                                            <th>Jabatan</th>
                                            <th>User</th>
                                            <th>Jml MHS</th>
                                            <th>Jml Matkul</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($query)): 
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['semester']) ?></td>
                                            <td><?= htmlspecialchars($row['jbtn_pnt'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row['nama_user'] ?? '-') ?></td>
                                            <td><?= $row['jml_mhs'] ?></td>
                                            <td><?= $row['jml_matkul'] ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="#" class="btn btn-warning btn-sm edit-btn" 
                                                       data-id="<?= $row['id_tu'] ?>"
                                                       data-toggle="modal" 
                                                       data-target="#editModal">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="transaksi_ujian.php?hapus=<?= $row['id_tu'] ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       onclick="return confirm('Yakin hapus data ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
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
    </section>
</div>

<!-- Modal Edit -->
<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Transaksi Ujian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="proses_transaksi_ujian.php">
                <div class="modal-body">
                    <input type="hidden" name="id_tu" id="edit_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Semester</label>
                                <input type="text" class="form-control" name="semester" id="edit_semester" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah MHS Prodi</label>
                                <input type="number" class="form-control" name="jml_mhs_prodi" id="edit_jml_mhs_prodi" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Mahasiswa</label>
                                <input type="number" class="form-control" name="jml_mhs" id="edit_jml_mhs" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Koreksi</label>
                                <input type="number" class="form-control" name="jml_koreksi" id="edit_jml_koreksi" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Mata Kuliah</label>
                                <input type="number" class="form-control" name="jml_matkul" id="edit_jml_matkul" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pengawas Pagi</label>
                                <input type="number" class="form-control" name="jml_pgws_pagi" id="edit_jml_pgws_pagi" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
include __DIR__ . '/includes/footer.php';
include __DIR__ . '/includes/footer_scripts.php';
?>

<!-- Page Specific JS -->
<script src="<?= ASSETS_URL ?>js/page/modules-datatables.js"></script>
<script>
$(document).ready(function() {
    // Inisialisasi DataTable
    $('#table-1').DataTable({
        "pageLength": 10,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data",
            "infoFiltered": "(disaring dari total _MAX_ data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Berikutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    // Handle klik tombol edit
    $('.edit-btn').click(function() {
        var id = $(this).data('id');
        
        // Ambil data via AJAX
        $.ajax({
            url: 'get_transaksi_ujian.php',
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    $('#edit_id').val(data.data.id_tu);
                    $('#edit_semester').val(data.data.semester);
                    $('#edit_jml_mhs_prodi').val(data.data.jml_mhs_prodi);
                    $('#edit_jml_mhs').val(data.data.jml_mhs);
                    $('#edit_jml_koreksi').val(data.data.jml_koreksi);
                    $('#edit_jml_matkul').val(data.data.jml_matkul);
                    $('#edit_jml_pgws_pagi').val(data.data.jml_pgws_pagi);
                    $('#editModal').modal('show');
                }
            }
        });
    });
});
</script>