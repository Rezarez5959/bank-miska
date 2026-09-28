<?php
header('Content-Type: application/json');
include "koneksi.php";

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    // Fallback if form POST is used instead of JSON
    $input = $_POST;
}

$uid_kartu = isset($input['uid_kartu']) ? trim($input['uid_kartu']) : '';
$nama = isset($input['nama']) ? trim($input['nama']) : '';
$nis = isset($input['nis']) && !empty(trim($input['nis'])) ? trim($input['nis']) : NULL;
$saldo = isset($input['saldo']) ? floatval($input['saldo']) : 0.00;

if (empty($uid_kartu) || empty($nama)) {
    echo json_encode(["sukses" => false, "pesan" => "ID Kartu RFID dan Nama Nasabah wajib diisi!"]);
    exit;
}

// Cek apakah UID Kartu sudah terdaftar
$check_uid = mysqli_query($koneksi, "SELECT id_akun, nama FROM tb_akun WHERE uid_kartu = '" . mysqli_real_escape_string($koneksi, $uid_kartu) . "'");
if (mysqli_num_rows($check_uid) > 0) {
    $existing = mysqli_fetch_assoc($check_uid);
    echo json_encode([
        "sukses" => false, 
        "pesan" => "ID Kartu RFID (" . htmlspecialchars($uid_kartu) . ") sudah terdaftar atas nama: " . htmlspecialchars($existing['nama'])
    ]);
    exit;
}

// Cek NIS jika diisi
if ($nis !== NULL) {
    $check_nis = mysqli_query($koneksi, "SELECT id_akun, nama FROM tb_akun WHERE nis = '" . mysqli_real_escape_string($koneksi, $nis) . "'");
    if (mysqli_num_rows($check_nis) > 0) {
        $existing_nis = mysqli_fetch_assoc($check_nis);
        echo json_encode([
            "sukses" => false, 
            "pesan" => "NIS (" . htmlspecialchars($nis) . ") sudah terdaftar atas nama: " . htmlspecialchars($existing_nis['nama'])
        ]);
        exit;
    }
}

// Insert ke tb_akun
$role = 'user';
$stmt = mysqli_prepare($koneksi, "INSERT INTO tb_akun (role, nama, nis, uid_kartu, saldo) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssssd", $role, $nama, $nis, $uid_kartu, $saldo);

if (mysqli_stmt_execute($stmt)) {
    $id_baru = mysqli_insert_id($koneksi);
    
    // Jika ada saldo awal > 0, bisa sekalian dicatat transaksi topup awal
    if ($saldo > 0) {
        $keterangan = 'Saldo awal pendaftaran nasabah baru';
        $jenis_transaksi = 'topup';
        $saldo_awal = 0.00;
        $saldo_akhir = $saldo;
        
        $stmt_tx = mysqli_prepare($koneksi, "INSERT INTO tb_transaksi (id_user, jenis_transaksi, total_bayar, saldo_awal, saldo_akhir, status, keterangan) VALUES (?, ?, ?, ?, ?, 'berhasil', ?)");
        mysqli_stmt_bind_param($stmt_tx, "isddds", $id_baru, $jenis_transaksi, $saldo, $saldo_awal, $saldo_akhir, $keterangan);
        mysqli_stmt_execute($stmt_tx);
        mysqli_stmt_close($stmt_tx);
    }
    
    mysqli_stmt_close($stmt);
    echo json_encode([
        "sukses" => true,
        "pesan" => "Nasabah berhasil ditambahkan!",
        "id_akun" => $id_baru,
        "nama" => $nama,
        "uid_kartu" => $uid_kartu,
        "saldo" => $saldo
    ]);
} else {
    echo json_encode(["sukses" => false, "pesan" => "Gagal menyimpan data: " . mysqli_error($koneksi)]);
}
?>
