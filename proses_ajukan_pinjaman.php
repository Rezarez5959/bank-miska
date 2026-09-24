<?php
include "koneksi.php";

$input = json_decode(file_get_contents("php://input"), true);

$id_user = $input['id_akun'];
$plafon = $input['plafon'];
$bunga = $input['bunga'];
$tenor = $input['tenor'];

// Hitung langsung total yang harus dibayar nanti (pokok + bunga)
// sisa_piutang di awal = total_harus_dibayar (belum ada cicilan sama sekali)
$total_harus_dibayar = $plafon + ($plafon * $bunga / 100);
$sisa_piutang = $total_harus_dibayar;

// id_teller sengaja belum diisi (NULL) -- akan diisi setelah
// sistem login khusus teller dibuat di tahap berikutnya
$insert = mysqli_query($koneksi, "
    INSERT INTO tb_piutang
    (id_user, plafon_pinjaman, bunga_persen, total_harus_dibayar, sisa_piutang, tenor_bulan, status)
    VALUES
    ('$id_user', '$plafon', '$bunga', '$total_harus_dibayar', '$sisa_piutang', '$tenor', 'diajukan')
");

if (!$insert) {
    echo json_encode(["sukses" => false, "pesan" => "Gagal mengajukan pinjaman: " . mysqli_error($koneksi)]);
    exit;
}

echo json_encode([
    "sukses" => true,
    "total_harus_dibayar" => $total_harus_dibayar
]);
