<?php
include "koneksi.php";

// Ambil semua pengajuan yang masih menunggu persetujuan
$query = mysqli_query($koneksi, "
    SELECT tb_piutang.*, tb_akun.nama, tb_akun.nis
    FROM tb_piutang
    JOIN tb_akun ON tb_piutang.id_user = tb_akun.id_akun
    WHERE tb_piutang.status = 'diajukan'
    ORDER BY tb_piutang.tanggal_pengajuan ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Approval Pinjaman - Bank Miska</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #2563eb;
      --success: #16a34a;
      --danger: #dc2626;
      --bg: #f8fafc;
      --card-bg: #ffffff;
      --text: #0f172a;
      --text-muted: #64748b;
      --border: #e2e8f0;
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
      background-color: var(--bg);
      color: var(--text);
      padding: 20px;
    }

    .header-bar {
      max-width: 1100px;
      margin: 0 auto 24px auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: var(--card-bg);
      padding: 16px 24px;
      border-radius: 12px;
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
    }

    .brand-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--primary);
    }

    .nav-btn {
      text-decoration: none;
      color: var(--text-muted);
      font-size: 0.9rem;
      font-weight: 600;
      padding: 8px 16px;
      border-radius: 8px;
      background: #f1f5f9;
      transition: all 0.2s;
    }

    .nav-btn:hover {
      background: #e2e8f0;
      color: var(--text);
    }

    .container {
      max-width: 1100px;
      margin: 0 auto;
      background: var(--card-bg);
      padding: 24px;
      border-radius: 12px;
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
    }

    h3 {
      font-size: 1.15rem;
      font-weight: 700;
      margin-bottom: 18px;
      color: var(--text);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px 14px;
      text-align: left;
      border-bottom: 1px solid var(--border);
      font-size: 0.9rem;
    }

    th {
      background-color: #f1f5f9;
      color: var(--text-muted);
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    tr:hover {
      background-color: #f8fafc;
    }

    .btn-action {
      padding: 6px 14px;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      font-size: 0.85rem;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .btn-setuju {
      background-color: var(--success);
      color: white;
      margin-right: 4px;
    }

    .btn-setuju:hover {
      background-color: #15803d;
    }

    .btn-tolak {
      background-color: var(--danger);
      color: white;
    }

    .btn-tolak:hover {
      background-color: #b91c1c;
    }

    .empty-state {
      text-align: center;
      padding: 30px;
      color: var(--text-muted);
      font-style: italic;
    }
  </style>
</head>
<body>

  <div class="header-bar">
    <div class="brand-title">
      📑 Approval Pinjaman
    </div>
    <div>
      <a href="menu.html" class="nav-btn">&larr; Menu Utama</a>
      <a href="dashboard_miska.php" class="nav-btn" style="margin-left: 8px;">💳 Dashboard Miska</a>
    </div>
  </div>

  <div class="container">
    <h3>Daftar Pengajuan Pinjaman (Menunggu Persetujuan)</h3>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Nasabah</th>
          <th>NIS</th>
          <th>Plafon</th>
          <th>Bunga</th>
          <th>Total Harus Dibayar</th>
          <th>Tenor</th>
          <th>Tanggal Ajuan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if (mysqli_num_rows($query) > 0) {
          while ($data = mysqli_fetch_assoc($query)) { 
        ?>
        <tr>
          <td>#<?php echo $data['id_piutang']; ?></td>
          <td><strong><?php echo htmlspecialchars($data['nama']); ?></strong></td>
          <td><?php echo htmlspecialchars($data['nis']); ?></td>
          <td style="color: #2563eb; font-weight: 600;">Rp <?php echo number_format($data['plafon_pinjaman'], 0, ',', '.'); ?></td>
          <td><?php echo $data['bunga_persen']; ?>%</td>
          <td style="font-weight: 600;">Rp <?php echo number_format($data['total_harus_dibayar'], 0, ',', '.'); ?></td>
          <td><?php echo $data['tenor_bulan']; ?> bulan</td>
          <td><?php echo date('d-m-Y H:i', strtotime($data['tanggal_pengajuan'])); ?></td>
          <td>
            <button class="btn-action btn-setuju" onclick="setujui(<?php echo $data['id_piutang']; ?>)">Setujui</button>
            <button class="btn-action btn-tolak" onclick="tolak(<?php echo $data['id_piutang']; ?>)">Tolak</button>
          </td>
        </tr>
        <?php 
          } 
        } else {
        ?>
        <tr>
          <td colspan="9" class="empty-state">Tidak ada pengajuan pinjaman yang menunggu persetujuan.</td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    <div id="pesan" style="margin-top: 16px; font-weight: 600; color: #dc2626;"></div>
  </div>

  <script src="approval.js"></script>
</body>
</html>
