<?php
/**
 * DATA HONOR DOSEN - SiPagu
 * Halaman untuk melihat, mengedit, menghapus data honor dosen
 * Lokasi: admin/honor_dosen.php
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config.php';

$page_title = "Data Honor Dosen";

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $delete = mysqli_query($koneksi, "DELETE FROM t_transaksi_honor_dosen WHERE id_thd = '$id'");
    
    if ($delete) {
        $_SESSION['success_message'] = "Data honor dosen berhasil dihapus!";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus data: " . mysqli_error($koneksi);
    }
    header("Location: honor_dosen.php");
    exit;
}

// Ambil data
$query = mysqli_query($koneksi, "
    SELECT th.*, j.nama_matkul, u.nama_user 
    FROM t_transaksi_honor_dosen th
    LEFT JOIN t_jadwal j ON th.id_jadwal = j.id_jdwl
    LEFT JOIN t_user u ON j.id_user = u.id_user
    ORDER BY th.id_thd DESC
");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
include __DIR__ . '/includes/sidebar_admin.php';
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Honor Dosen</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= BASE_URL ?>admin/index.php">Dashboard</a></div>
                <div class="breadcrumb-item">Master Data</div>
                <div class="breadcrumb-item">Data Honor Dosen</div>
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
                            <h4>Daftar Honor Dosen</h4>
                            <div class="card-header-action">
                                <a href="<?= BASE_URL ?>admin/hitung_honor.php" class="btn btn-primary">
                                    <i class="fas fa-calculator"></i> Hitung Honor
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
                                            <th>Bulan</th>
                                            <th>Mata Kuliah</th>
                                            <th>Dosen</th>
                                            <th>Jml TM</th>
                                            <th>SKS</th>
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
                                            <td><?= ucfirst(htmlspecialchars($row['bulan'])) ?></td>
                                            <td><?= htmlspecialchars($row['nama_matkul'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row['nama_user'] ?? '-') ?></td>
                                            <td><?= $row['jml_tm'] ?></td>
                                            <td><?= $row['sks_tempuh'] ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="#" class="btn btn-warning btn-sm edit-btn" 
                                                       data-id="<?= $row['id_thd'] ?>"
                                                       data-toggle="modal" 
                                                       data-target="#editModal">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="honor_dosen.php?hapus=<?= $row['id_thd'] ?>" 
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
                <h5 class="modal-title">Edit Data Honor Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="proses_honor_dosen.php">
                <div class="modal-body">
                    <input type="hidden" name="id_thd" id="edit_id">
                    <div class="form-group">
                        <label>Semester</label>
                        <input type="text" class="form-control" name="semester" id="edit_semester" required>
                    </div>
                    <div class="form-group">
                        <label>Bulan</label>
                        <select class="form-control" name="bulan" id="edit_bulan" required>
                            <option value="januari">Januari</option>
                            <option value="februari">Februari</option>
                            <option value="maret">Maret</option>
                            <option value="april">April</option>
                            <option value="mei">Mei</option>
                            <option value="juni">Juni</option>
                            <option value="juli">Juli</option>
                            <option value="agustus">Agustus</option>
                            <option value="september">September</option>
                            <option value="oktober">Oktober</option>
                            <option value="november">November</option>
                            <option value="desember">Desember</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah TM</label>
                        <input type="number" class="form-control" name="jml_tm" id="edit_jml_tm" required>
                    </div>
                    <div class="form-group">
                        <label>SKS Tempuh</label>
                        <input type="number" class="form-control" name="sks_tempuh" id="edit_sks_tempuh" required>
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
            url: 'get_honor_dosen.php',
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    $('#edit_id').val(data.data.id_thd);
                    $('#edit_semester').val(data.data.semester);
                    $('#edit_bulan').val(data.data.bulan);
                    $('#edit_jml_tm').val(data.data.jml_tm);
                    $('#edit_sks_tempuh').val(data.data.sks_tempuh);
                    $('#editModal').modal('show');
                }
            }
        });
    });
});
</script>