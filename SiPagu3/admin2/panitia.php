<?php
/**
 * DATA PANITIA - SiPagu
 * Halaman untuk melihat, mengedit, menghapus data panitia
 * Lokasi: admin/panitia.php
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config.php';

$page_title = "Data Panitia";

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $delete = mysqli_query($koneksi, "DELETE FROM t_panitia WHERE id_pnt = '$id'");
    
    if ($delete) {
        $_SESSION['success_message'] = "Data panitia berhasil dihapus!";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus data: " . mysqli_error($koneksi);
    }
    header("Location: panitia.php");
    exit;
}

// Ambil data
$query = mysqli_query($koneksi, "SELECT * FROM t_panitia ORDER BY id_pnt DESC");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
include __DIR__ . '/includes/sidebar_admin.php';
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Panitia</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item">Master Data</div>
                <div class="breadcrumb-item">Data Panitia</div>
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
                            <h4>Daftar Panitia</h4>
                            <div class="card-header-action">
                                <a href="<?= BASE_URL ?>admin/upload_panitia.php" class="btn btn-primary">
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
                                            <th>Jabatan</th>
                                            <th>Honor Standar</th>
                                            <th>Honor Periode 1</th>
                                            <th>Honor Periode 2</th>
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
                                            <td><strong><?= htmlspecialchars($row['jbtn_pnt']) ?></strong></td>
                                            <td>Rp <?= number_format($row['honor_std'], 0, ',', '.') ?></td>
                                            <td>Rp <?= number_format($row['honor_p1'], 0, ',', '.') ?></td>
                                            <td>Rp <?= number_format($row['honor_p2'], 0, ',', '.') ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="#" class="btn btn-warning btn-sm edit-btn" 
                                                       data-id="<?= $row['id_pnt'] ?>"
                                                       data-toggle="modal" 
                                                       data-target="#editModal">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="panitia.php?hapus=<?= $row['id_pnt'] ?>" 
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Panitia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="proses_panitia.php">
                <div class="modal-body">
                    <input type="hidden" name="id_pnt" id="edit_id">
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" name="jbtn_pnt" id="edit_jbtn_pnt" required>
                    </div>
                    <div class="form-group">
                        <label>Honor Standar</label>
                        <input type="number" class="form-control" name="honor_std" id="edit_honor_std" required>
                    </div>
                    <div class="form-group">
                        <label>Honor Periode 1</label>
                        <input type="number" class="form-control" name="honor_p1" id="edit_honor_p1" required>
                    </div>
                    <div class="form-group">
                        <label>Honor Periode 2</label>
                        <input type="number" class="form-control" name="honor_p2" id="edit_honor_p2" required>
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
            url: 'get_panitia.php',
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    $('#edit_id').val(data.data.id_pnt);
                    $('#edit_jbtn_pnt').val(data.data.jbtn_pnt);
                    $('#edit_honor_std').val(data.data.honor_std);
                    $('#edit_honor_p1').val(data.data.honor_p1);
                    $('#edit_honor_p2').val(data.data.honor_p2);
                    $('#editModal').modal('show');
                }
            }
        });
    });
});
</script>