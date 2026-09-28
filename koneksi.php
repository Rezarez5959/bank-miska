<?php
$db_name = "db_ukkcashless";
$koneksi = @mysqli_connect("localhost", "root", "", $db_name);

if (!$koneksi) {
    // Fallback try db_ukk_cashless
    $koneksi = mysqli_connect("localhost", "root", "", "db_ukk_cashless");
}

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
