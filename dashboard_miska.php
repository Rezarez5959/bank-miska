<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Bank Miska</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #2563eb;
      --primary-hover: #1d4ed8;
      --success: #16a34a;
      --success-hover: #15803d;
      --warning: #d97706;
      --bg: #f8fafc;
      --card-bg: #ffffff;
      --text: #0f172a;
      --text-muted: #64748b;
      --border: #e2e8f0;
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
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
      max-width: 1000px;
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
      display: flex;
      align-items: center;
      gap: 10px;
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
      max-width: 1000px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
    }

    @media (min-width: 768px) {
      .container {
        grid-template-columns: 1fr 1fr;
      }
      .full-width {
        grid-column: span 2;
      }
    }

    .card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 20px;
      box-shadow: var(--shadow);
    }

    .card-title {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-group {
      margin-bottom: 12px;
    }

    .form-group label {
      display: block;
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--text-muted);
      margin-bottom: 6px;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 0.95rem;
      outline: none;
      transition: border-color 0.2s;
    }

    input[type="text"]:focus,
    input[type="number"]:focus {
      border-color: var(--primary);
    }

    .btn {
      width: 100%;
      padding: 10px 16px;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.9rem;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: var(--primary-hover); }

    .btn-success { background: var(--success); color: white; }
    .btn-success:hover { background: var(--success-hover); }

    .btn-warning { background: var(--warning); color: white; }
    .btn-warning:hover { background: #b45309; }

    .info-box {
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      padding: 14px;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 600;
      color: #1e40af;
      margin-top: 10px;
      display: none;
    }

    .alert-box {
      background: #fef3c7;
      border: 1px solid #fde68a;
      color: #92400e;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 0.9rem;
      margin-top: 16px;
      display: none;
    }
  </style>
</head>
<body>

  <div class="header-bar">
    <div class="brand-title">
      <span>🏦</span> Dashboard Bank Miska
    </div>
    <div>
      <a href="./index.html" class="nav-btn">&larr; Menu Utama</a>
      <a href="./tambah_nasabah.php" class="nav-btn" style="margin-left: 8px; background: #dbeafe; color: #1e40af;">➕ Tambah Nasabah</a>
      <a href="./history_keuangan.php" class="nav-btn" style="margin-left: 8px; background: #e0e7ff; color: #3730a3;">📊 History Keuangan</a>
    </div>
  </div>

  <div class="container">
    
    <!-- 1. Cari Nasabah -->
    <div class="card full-width">
      <div class="card-title">🔍 1. Cari Nasabah</div>
      <div style="display: flex; gap: 10px;">
        <input type="text" id="uid" placeholder="Masukkan UID Kartu RFID">
        <button class="btn btn-primary" onclick="cariNasabah()" style="width: auto; white-space: nowrap;">Cari Nasabah</button>
      </div>
      <div id="info-nasabah" class="info-box"></div>
    </div>

    <!-- 2. Top Up -->
    <div class="card">
      <div class="card-title">💵 2. Top Up Saldo</div>
      <div class="form-group">
        <label for="nominal-topup">Nominal Top Up (Rp):</label>
        <input type="number" id="nominal-topup" placeholder="Contoh: 50000">
      </div>
      <button class="btn btn-success" onclick="prosesTopup()">Proses Top Up</button>
    </div>

    <!-- 3. Tarik Dana -->
    <div class="card">
      <div class="card-title">💸 3. Tarik Dana Tunai</div>
      <div class="form-group">
        <label for="nominal-tarik">Nominal Tarik (Rp):</label>
        <input type="number" id="nominal-tarik" placeholder="Contoh: 20000">
      </div>
      <button class="btn btn-warning" onclick="prosesTarik()">Proses Tarik Dana</button>
    </div>

    <!-- 4. Ajukan Pinjaman -->
    <div class="card full-width">
      <div class="card-title">📝 4. Ajukan Pinjaman Baru</div>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
        <div class="form-group">
          <label for="plafon-pinjaman">Plafon Pinjaman (Rp):</label>
          <input type="number" id="plafon-pinjaman" placeholder="100000">
        </div>
        <div class="form-group">
          <label for="bunga-pinjaman">Bunga (%):</label>
          <input type="number" step="0.1" id="bunga-pinjaman" placeholder="1.0">
        </div>
        <div class="form-group">
          <label for="tenor-pinjaman">Tenor (Bulan):</label>
          <input type="number" id="tenor-pinjaman" placeholder="12">
        </div>
      </div>
      <button class="btn btn-primary" onclick="ajukanPinjaman()" style="margin-top: 8px;">Kirim Pengajuan Pinjaman</button>
    </div>

  </div>

  <div class="header-bar" style="margin-top: 20px;">
    <div id="pesan" style="font-size: 0.9rem; font-weight: 600; color: #1e293b;">Status aksi akan muncul di sini.</div>
  </div>

  <script src="./miska.js"></script>
  <script>
    // Penyesuaian helper JS agar tampilan info-nasabah dapat otomatis tampil saat dicari
    const origCari = window.cariNasabah;
    const infoBox = document.getElementById("info-nasabah");
    
    // Observer sederhana untuk menyesuaikan style info-nasabah
    const observer = new MutationObserver(function() {
      if (infoBox.innerHTML.trim() !== "") {
        infoBox.style.display = "block";
      } else {
        infoBox.style.display = "none";
      }
    });
    observer.observe(infoBox, { childList: true, subtree: true });
  </script>
</body>
</html>
