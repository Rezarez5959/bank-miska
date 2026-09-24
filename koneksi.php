<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_ukk_cashless");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
