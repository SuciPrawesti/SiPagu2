<?php
session_start();
include 'koneksi.php';

$npp_user = $_POST['npp_user'];
$pw_user  = MD5($_POST['pw_user']);

$data = mysqli_query($koneksi, 
    "SELECT * FROM t_user 
     WHERE npp_user='$npp_user' 
     AND pw_user='$pw_user'"
);

$cek = mysqli_num_rows($data);

if ($cek > 0) {
    $row = mysqli_fetch_array($data);

    // SESSION
    $_SESSION['id_user'] = $row['id_user'];
    $_SESSION['npp_user'] = $row['npp_user'];
    $_SESSION['nik_user'] = $row['nik_user'];
    $_SESSION['npwp_user'] = $row['npwp_user'];
    $_SESSION['norek_user'] = $row['norek_user'];
    $_SESSION['nama_user'] = $row['nama_user'];
    $_SESSION['nohp_user'] = $row['nohp_user'];
    $_SESSION['role_user'] = $row['role_user'];
    $_SESSION['honor_persks'] = $row['honor_persks'];
    $_SESSION['status_user'] = "login";

    // INGAT SAYA
    if (isset($_POST['remember'])) {
        $token = bin2hex(random_bytes(32));

        mysqli_query($koneksi,
            "UPDATE t_user 
             SET remember_token='$token' 
             WHERE id_user='{$row['id_user']}'"
        );

        setcookie(
            'remember_token',
            $token,
            time() + (60 * 60 * 24 * 7),
            '/',
            '',
            false,
            true
        );
    }

    // REDIRECT ROLE
    if ($_SESSION['role_user'] == 'admin') {
        header("Location: admin/index.php");
    } else if ($_SESSION['role_user'] == 'koordinator') {
        header("Location: koordinator/index.php");
    } else if ($_SESSION['role_user'] == 'staff') {
        header("Location: staff/index.php");
    }

} else {
    header("Location: login.php?pesan=gagal");
}
?>