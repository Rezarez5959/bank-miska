<?php
include "koneksi.php";

$input = json_decode(file_get_contents("php://input"), true);

$id_piutang = $input['id_piutang'];
$aksi = $input['aksi']; // 'setuju' atau 'tolak'


// ======================================================
// AKSI: TOLAK
// ======================================================
if ($aksi == "tolak") {
    mysqli_query($koneksi, "UPDATE tb_piutang SET status = 'ditolak' WHERE id_piutang = '$id_piutang'");

    echo json_encode(["sukses" => true]);
    exit;
}


// ======================================================
// AKSI: SETUJUI (sekaligus cairkan + generate jadwal angsuran)
// ======================================================
if ($aksi == "setuju") {

    // Ambil dulu data pinjamannya
    $query = mysqli_query($koneksi, "SELECT * FROM tb_piutang WHERE id_piutang = '$id_piutang'");
    $piutang = mysqli_fetch_assoc($query);

    if (!$piutang) {
        echo json_encode(["sukses" => false, "pesan" => "Data pinjaman tidak ditemukan"]);
        exit;
    }

    $id_user = $piutang['id_user'];
    $plafon = $piutang['plafon_pinjaman'];
    $total_harus_dibayar = $piutang['total_harus_dibayar'];
    $tenor = $piutang['tenor_bulan'];

    // 1. Update status pinjaman jadi berjalan + catat tanggal
    mysqli_query($koneksi, "
        UPDATE tb_piutang
        SET status = 'berjalan', tanggal_disetujui = NOW(), tanggal_pencairan = NOW()
        WHERE id_piutang = '$id_piutang'
    ");

    // 2. Cairkan dana: saldo nasabah bertambah sejumlah plafon
    $query_saldo = mysqli_query($koneksi, "SELECT saldo FROM tb_akun WHERE id_akun = '$id_user'");
    $akun = mysqli_fetch_assoc($query_saldo);

    $saldo_awal = $akun['saldo'];
    $saldo_akhir = $saldo_awal + $plafon;

    mysqli_query($koneksi, "UPDATE tb_akun SET saldo = '$saldo_akhir' WHERE id_akun = '$id_user'");

    // 3. Catat pencairan ini di tb_transaksi (supaya masuk riwayat gabungan nanti)
    mysqli_query($koneksi, "
        INSERT INTO tb_transaksi
        (id_user, jenis_transaksi, total_bayar, saldo_awal, saldo_akhir, status, keterangan)
        VALUES
        ('$id_user', 'pinjaman', '$plafon', '$saldo_awal', '$saldo_akhir', 'berhasil', 'Pencairan pinjaman')
    ");

    // 4. Generate jadwal angsuran sebanyak tenor bulan, dibagi rata
    $nominal_per_angsuran = round($total_harus_dibayar / $tenor);

    for ($i = 1; $i <= $tenor; $i++) {
        $jatuh_tempo = date("Y-m-d", strtotime("+$i month"));

        mysqli_query($koneksi, "
            INSERT INTO tb_jadwal_angsuran
            (id_piutang, angsuran_ke, nominal_angsuran, tanggal_jatuh_tempo, status)
            VALUES
            ('$id_piutang', '$i', '$nominal_per_angsuran', '$jatuh_tempo', 'belum_dibayar')
        ");
    }

    echo json_encode(["sukses" => true]);
    exit;
}

echo json_encode(["sukses" => false, "pesan" => "Aksi tidak dikenali"]);
