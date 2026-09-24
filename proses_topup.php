<?php
include "koneksi.php";

$input = json_decode(file_get_contents("php://input"), true);

$id_user = $input['id_akun'];
$nominal = $input['nominal'];

// Ambil saldo nasabah saat ini
$query = mysqli_query($koneksi, "SELECT saldo FROM tb_akun WHERE id_akun = '$id_user'");
$akun = mysqli_fetch_assoc($query);

$saldo_awal = $akun['saldo'];
$saldo_akhir = $saldo_awal + $nominal;

// Catat transaksi. Kolom id_admin, id_menu, qty, harga_satuan sengaja
// tidak diisi (otomatis NULL) karena topup tidak melibatkan menu/kasir.
$insert = mysqli_query($koneksi, "
    INSERT INTO tb_transaksi
    (id_user, jenis_transaksi, total_bayar, saldo_awal, saldo_akhir, status, keterangan)
    VALUES
    ('$id_user', 'topup', '$nominal', '$saldo_awal', '$saldo_akhir', 'berhasil', 'Top up melalui sistemMiska')
");

if (!$insert) {
    echo json_encode(["sukses" => false, "pesan" => "Gagal insert: " . mysqli_error($koneksi)]);
    exit;
}

$update = mysqli_query($koneksi, "UPDATE tb_akun SET saldo = '$saldo_akhir' WHERE id_akun = '$id_user'");

if (!$update) {
    echo json_encode(["sukses" => false, "pesan" => "Gagal update saldo: " . mysqli_error($koneksi)]);
    exit;
}

echo json_encode([
    "sukses" => true,
    "saldo_akhir" => $saldo_akhir
]);
