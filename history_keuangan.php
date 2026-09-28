<?php
include "koneksi.php";

$id_user = isset($_GET['id_user']) ? intval($_GET['id_user']) : 0;
$jenis   = isset($_GET['jenis']) ? $_GET['jenis'] : '';
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';

// Query daftar nasabah untuk filter dropdown
$nasabah_query = mysqli_query($koneksi, "SELECT id_akun, nama, nis FROM tb_akun WHERE role = 'user' ORDER BY nama ASC");

// Build klausa WHERE untuk transaksi (Hanya topup, tarik, pinjaman)
$where = ["t.jenis_transaksi IN ('topup', 'tarik', 'pinjaman')"];

if ($id_user > 0) {
    $where[] = "t.id_user = '$id_user'";
}
if (!empty($jenis) && in_array($jenis, ['topup', 'tarik', 'pinjaman'])) {
    $jenis_clean = mysqli_real_escape_string($koneksi, $jenis);
    $where[] = "t.jenis_transaksi = '$jenis_clean'";
}
if (!empty($tgl_mulai)) {
    $tgl_m_clean = mysqli_real_escape_string($koneksi, $tgl_mulai . " 00:00:00");
    $where[] = "t.waktu_transaksi >= '$tgl_m_clean'";
}
if (!empty($tgl_selesai)) {
    $tgl_s_clean = mysqli_real_escape_string($koneksi, $tgl_selesai . " 23:59:59");
    $where[] = "t.waktu_transaksi <= '$tgl_s_clean'";
}

$where_clause = "WHERE " . implode(" AND ", $where);

// Query mengambil riwayat kegiatan transaksi keuangan
$sql = "
    SELECT 
        t.id_transaksi,
        t.jenis_transaksi,
        t.total_bayar,
        t.saldo_awal,
        t.saldo_akhir,
        t.status,
        t.keterangan,
        t.waktu_transaksi,
        u.nama AS nama_nasabah,
        u.nis AS nis_nasabah,
        m.nama AS nama_menu,
        a.nama AS nama_admin
    FROM tb_transaksi t
    JOIN tb_akun u ON t.id_user = u.id_akun
    LEFT JOIN tb_menu m ON t.id_menu = m.id_menu
    LEFT JOIN tb_akun a ON t.id_admin = a.id_akun
    $where_clause
    ORDER BY t.waktu_transaksi DESC
";
$transaksi_query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Kegiatan Keuangan - Bank Miska</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        h2 {
            margin-top: 0;
            color: #1a365d;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .filter-card {
            background-color: #edf2f7;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .filter-card form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex: 1;
            min-width: 180px;
        }
        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
        }
        .form-group select, .form-group input {
            padding: 8px 12px;
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            background: #fff;
        }
        .btn-filter {
            background-color: #3182ce;
            color: white;
            padding: 9px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .btn-filter:hover {
            background-color: #2b6cb0;
        }
        .btn-reset {
            background-color: #e2e8f0;
            color: #4a5568;
            padding: 9px 15px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
        }
        .btn-reset:hover {
            background-color: #cbd5e0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        th {
            background-color: #2b6cb0;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        tr:hover {
            background-color: #f7fafc;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-topup { background-color: #c6f6d5; color: #22543d; }
        .badge-tarik { background-color: #feebc8; color: #744210; }
        .badge-bayar { background-color: #e9d8fd; color: #441239; }
        .badge-pinjaman { background-color: #bee3f8; color: #2b6cb0; }
        .badge-angsuran { background-color: #fed7d7; color: #9b2c2c; }
        .status-berhasil { color: #38a169; font-weight: bold; }
        .status-dibatalkan { color: #e53e3e; font-weight: bold; }
        .nominal-masuk { color: #2f855a; font-weight: bold; }
        .nominal-keluar { color: #c53030; font-weight: bold; }
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #a0aec0;
            font-style: italic;
        }
        .nav-links {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .nav-links a {
            color: #3182ce;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>
        <span>History Keluar Masuk Kegiatan Keuangan</span>
        <span style="font-size: 14px; color: #718096; font-weight: normal;">Bank Miska</span>
    </h2>

    <!-- Form Filter -->
    <div class="filter-card">
        <form method="GET" action="history_keuangan.php">
            <div class="form-group">
                <label for="id_user">Nasabah:</label>
                <select name="id_user" id="id_user">
                    <option value="">-- Semua Nasabah --</option>
                    <?php while ($n = mysqli_fetch_assoc($nasabah_query)) { ?>
                        <option value="<?php echo $n['id_akun']; ?>" <?php if ($id_user == $n['id_akun']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($n['nama']); ?> (<?php echo htmlspecialchars($n['nis']); ?>)
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="jenis">Jenis Transaksi:</label>
                <select name="jenis" id="jenis">
                    <option value="">-- Semua Jenis --</option>
                    <option value="topup" <?php if ($jenis == 'topup') echo 'selected'; ?>>Top Up (Masuk)</option>
                    <option value="pinjaman" <?php if ($jenis == 'pinjaman') echo 'selected'; ?>>Pencairan Pinjaman (Masuk)</option>
                    <option value="tarik" <?php if ($jenis == 'tarik') echo 'selected'; ?>>Tarik Dana (Keluar)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tgl_mulai">Dari Tanggal:</label>
                <input type="date" name="tgl_mulai" id="tgl_mulai" value="<?php echo htmlspecialchars($tgl_mulai); ?>">
            </div>

            <div class="form-group">
                <label for="tgl_selesai">Sampai Tanggal:</label>
                <input type="date" name="tgl_selesai" id="tgl_selesai" value="<?php echo htmlspecialchars($tgl_selesai); ?>">
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn-filter">Filter</button>
                <a href="./history_keuangan.php" class="btn-reset">Reset</a>
            </div>
        </form>
    </div>

    <!-- Tabel History Transaksi -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Waktu</th>
                <th>Nasabah</th>
                <th>Jenis</th>
                <th>Detail / Menu</th>
                <th>Nominal</th>
                <th>Saldo Awal</th>
                <th>Saldo Akhir</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (mysqli_num_rows($transaksi_query) > 0) {
                while ($row = mysqli_fetch_assoc($transaksi_query)) { 
                    $j = $row['jenis_transaksi'];
                    $is_masuk = ($j == 'topup' || $j == 'pinjaman');
                    $badge_class = "badge-" . ($j ? $j : 'default');
                    
                    // Detail transaksi (menu kantin / admin)
                    $detail = "-";
                    if ($j == 'bayar' && !empty($row['nama_menu'])) {
                        $detail = "Pembelian: " . htmlspecialchars($row['nama_menu']);
                        if (!empty($row['nama_admin'])) {
                            $detail .= " (Kasir: " . htmlspecialchars($row['nama_admin']) . ")";
                        }
                    } elseif (!empty($row['nama_admin'])) {
                        $detail = "Proses Admin: " . htmlspecialchars($row['nama_admin']);
                    }
            ?>
                <tr>
                    <td>#<?php echo $row['id_transaksi']; ?></td>
                    <td><?php echo date('d-m-Y H:i', strtotime($row['waktu_transaksi'])); ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($row['nama_nasabah']); ?></strong><br>
                        <small style="color: #718096;"><?php echo htmlspecialchars($row['nis_nasabah']); ?></small>
                    </td>
                    <td>
                        <span class="badge <?php echo $badge_class; ?>">
                            <?php echo !empty($j) ? htmlspecialchars(strtoupper($j)) : 'LAINNYA'; ?>
                        </span>
                    </td>
                    <td><?php echo $detail; ?></td>
                    <td class="<?php echo $is_masuk ? 'nominal-masuk' : 'nominal-keluar'; ?>">
                        <?php echo $is_masuk ? '+' : '-'; ?> Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?>
                    </td>
                    <td>Rp <?php echo number_format($row['saldo_awal'], 0, ',', '.'); ?></td>
                    <td>Rp <?php echo number_format($row['saldo_akhir'], 0, ',', '.'); ?></td>
                    <td>
                        <span class="status-<?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($row['keterangan'] ? $row['keterangan'] : '-'); ?></td>
                </tr>
            <?php 
                }
            } else {
            ?>
                <tr>
                    <td colspan="10" class="empty-state">Tidak ada data history kegiatan keuangan yang ditemukan.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="nav-links">
        <a href="./index.html">&larr; Menu Utama</a> | 
        <a href="./dashboard_miska.php">Dashboard Miska</a> | 
        <a href="./tambah_nasabah.php">Tambah Nasabah</a> | 
        <a href="./dashboard_approval_pinjaman.php">Approval Pinjaman &rarr;</a>
    </div>
</div>

</body>
</html>
