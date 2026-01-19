<?php
/**
 * DATA USER - SiPagu
 * Halaman untuk melihat, mengedit, menghapus data user
 * Lokasi: admin/users.php
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config.php';

$page_title = "Data User";

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $delete = mysqli_query($koneksi, "DELETE FROM t_user WHERE id_user = '$id'");
    
    if ($delete) {
        $_SESSION['success_message'] = "Data user berhasil dihapus!";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus data: " . mysqli_error($koneksi);
    }
    header("Location: users.php");
    exit;
}

// Ambil data
$query = mysqli_query($koneksi, "SELECT * FROM t_user ORDER BY id_user DESC");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
include __DIR__ . '/includes/sidebar_admin.php';
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item">Master Data</div>
                <div class="breadcrumb-item">Data User</div>
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
                            <h4>Daftar User</h4>
                            <div class="card-header-action">
                                <a href="<?= BASE_URL ?>admin/upload_user.php" class="btn btn-primary">
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
                                            <th>NPP</th>
                                            <th>Nama</th>
                                            <th>Role</th>
                                            <th>No. HP</th>
                                            <th>NPWP</th>
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
                                            <td><strong><?= htmlspecialchars($row['npp_user']) ?></strong></td>
                                            <td><?= htmlspecialchars($row['nama_user']) ?></td>
                                            <td>
                                                <span class="badge badge-<?= 
                                                    $row['role_user'] == 'admin' ? 'primary' : 
                                                    ($row['role_user'] == 'koordinator' ? 'warning' : 'secondary')
                                                ?>">
                                                    <?= ucfirst($row['role_user']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($row['nohp_user']) ?></td>
                                            <td><?= htmlspecialchars($row['npwp_user']) ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="#" class="btn btn-warning btn-sm edit-btn" 
                                                       data-id="<?= $row['id_user'] ?>"
                                                       data-toggle="modal" 
                                                       data-target="#editModal">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="users.php?hapus=<?= $row['id_user'] ?>" 
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
                <h5 class="modal-title">Edit Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="proses_user.php">
                <div class="modal-body">
                    <input type="hidden" name="id_user" id="edit_id">
                    <div class="form-group">
                        <label>NPP</label>
                        <input type="text" class="form-control" name="npp_user" id="edit_npp_user" required readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_user" id="edit_nama_user" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="role_user" id="edit_role_user" required>
                            <option value="admin">Admin</option>
                            <option value="koordinator">Koordinator</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="text" class="form-control" name="nohp_user" id="edit_nohp_user" required>
                    </div>
                    <div class="form-group">
                        <label>NPWP</label>
                        <input type="text" class="form-control" name="npwp_user" id="edit_npwp_user" required>
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
            url: 'get_user.php',
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    $('#edit_id').val(data.data.id_user);
                    $('#edit_npp_user').val(data.data.npp_user);
                    $('#edit_nama_user').val(data.data.nama_user);
                    $('#edit_role_user').val(data.data.role_user);
                    $('#edit_nohp_user').val(data.data.nohp_user);
                    $('#edit_npwp_user').val(data.data.npwp_user);
                    $('#editModal').modal('show');
                }
            }
        });
    });
});
</script>