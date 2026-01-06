<?php
$koneksi=mysqli_connect("localhost", "root", "", "db_sistem_honor_udinus");

if(mysqli_connect_errno()){
    echo "koneksi :".mysqli_connect_error();
}
?>