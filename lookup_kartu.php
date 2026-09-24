<?php
include "koneksi.php";

$uid = $_GET['uid'];

// role di database ini isinya 'user' (bukan 'siswa')
$query = mysqli_query($koneksi, "SELECT * FROM tb_akun WHERE uid_kartu = '$uid' AND role = 'user'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo json_encode(["sukses" => false, "pesan" => "Kartu tidak terdaftar sebagai nasabah"]);
    exit;
}

echo json_encode([
    "sukses" => true,
    "id_akun" => $data['id_akun'],
    "nama" => $data['nama'],
    "nis" => $data['nis'],
    "saldo" => $data['saldo']
]);
