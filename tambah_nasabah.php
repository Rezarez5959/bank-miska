<?php
include "koneksi.php";

// Ambil daftar nasabah terkini
$nasabah_query = mysqli_query($koneksi, "SELECT * FROM tb_akun WHERE role = 'user' ORDER BY id_akun DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Nasabah Baru - Bank Miska</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #2563eb;
      --primary-hover: #1d4ed8;
      --success: #16a34a;
      --success-hover: #15803d;
      --danger: #dc2626;
      --warning: #d97706;
      --bg: #f8fafc;
      --card-bg: #ffffff;
      --text: #0f172a;
      --text-muted: #64748b;
      --border: #e2e8f0;
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
      --shadow-lg: 0 10px 25px -5px rgba(37, 99, 235, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
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
      padding: 24px 16px;
      min-height: 100vh;
    }

    .header-bar {
      max-width: 1100px;
      margin: 0 auto 24px auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: var(--card-bg);
      padding: 16px 24px;
      border-radius: 14px;
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
    }

    .brand-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--primary);
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .brand-logo {
      width: 38px;
      height: 38px;
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: white;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 1.1rem;
      box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
    }

    .nav-links {
      display: flex;
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
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .nav-btn:hover {
      background: #e2e8f0;
      color: var(--text);
    }

    .container {
      max-width: 1100px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr;
      gap: 24px;
    }

    @media (min-width: 900px) {
      .container {
        grid-template-columns: 420px 1fr;
      }
    }

    .card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 24px;
      box-shadow: var(--shadow);
    }

    .card-title {
      font-size: 1.15rem;
      font-weight: 700;
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 10px;
      color: var(--text);
    }

    .card-subtitle {
      font-size: 0.85rem;
      color: var(--text-muted);
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-label {
      display: block;
      font-size: 0.875rem;
      font-weight: 600;
      margin-bottom: 6px;
      color: var(--text);
    }

    .input-wrapper {
      position: relative;
    }

    .form-control {
      width: 100%;
      padding: 11px 14px;
      font-size: 0.95rem;
      border: 1px solid var(--border);
      border-radius: 10px;
      outline: none;
      transition: all 0.2s ease;
      background: #fdfdfd;
    }

    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
      background: #ffffff;
    }

    .rfid-badge-info {
      font-size: 0.75rem;
      color: #3b82f6;
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      padding: 6px 10px;
      border-radius: 6px;
      margin-top: 6px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .btn-submit {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 10px;
    }

    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
    }

    .btn-submit:disabled {
      opacity: 0.65;
      cursor: not-allowed;
      transform: none;
    }

    .alert {
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 0.875rem;
      margin-bottom: 18px;
      display: none;
    }

    .alert-success {
      background-color: #f0fdf4;
      color: #15803d;
      border: 1px solid #bbf7d0;
    }

    .alert-danger {
      background-color: #fef2f2;
      color: #b91c1c;
      border: 1px solid #fecaca;
    }

    /* Table styles */
    .table-container {
      overflow-x: auto;
      border-radius: 10px;
      border: 1px solid var(--border);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 0.875rem;
    }

    th {
      background-color: #f8fafc;
      padding: 12px 16px;
      font-weight: 600;
      color: var(--text-muted);
      border-bottom: 1px solid var(--border);
    }

    td {
      padding: 12px 16px;
      border-bottom: 1px solid var(--border);
      color: var(--text);
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:hover {
      background-color: #f1f5f9/50;
    }

    .rfid-tag {
      font-family: monospace;
      background: #eff6ff;
      color: #2563eb;
      padding: 3px 8px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 0.85rem;
      border: 1px solid #bfdbfe;
    }

    .search-box {
      margin-bottom: 16px;
      display: flex;
      gap: 10px;
    }

    .search-input {
      flex: 1;
      padding: 9px 14px;
      border-radius: 8px;
      border: 1px solid var(--border);
      font-size: 0.875rem;
      outline: none;
    }

    .search-input:focus {
      border-color: var(--primary);
    }

    .empty-state {
      text-align: center;
      padding: 30px 10px;
      color: var(--text-muted);
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

  <!-- Top Header Navigation -->
  <div class="header-bar">
    <div class="brand-title">
      <div class="brand-logo">M</div>
      <span>Bank Miska - Registrasi Nasabah</span>
    </div>
    <div class="nav-links">
      <a href="index.html" class="nav-btn">🏠 Menu Utama</a>
      <a href="dashboard_miska.php" class="nav-btn">💳 Dashboard Miska</a>
      <a href="dashboard_approval_pinjaman.php" class="nav-btn">📑 Approval Pinjaman</a>
      <a href="history_keuangan.php" class="nav-btn">📊 History Keuangan</a>
    </div>
  </div>

  <div class="container">
    
    <!-- Form Tambah Nasabah -->
    <div class="card">
      <div class="card-title">
        <span>👤</span>
        <span>Tambah Nasabah Baru</span>
      </div>
      <p class="card-subtitle">Daftarkan nasabah baru dengan menempelkan kartu RFID atau menginput ID kartu secara manual.</p>

      <div id="alertBox" class="alert"></div>

      <form id="formTambahNasabah">
        <div class="form-group">
          <label class="form-label" for="uid_kartu">ID Kartu RFID / UID <span style="color:red;">*</span></label>
          <div class="input-wrapper">
            <input type="text" id="uid_kartu" name="uid_kartu" class="form-control" placeholder="Contoh: 0067305985" autofocus required autocomplete="off">
          </div>
          <div class="rfid-badge-info">
            📡 Tempelkan kartu pada scanner RFID atau ketik ID manual.
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="nama">Nama Lengkap Nasabah <span style="color:red;">*</span></label>
          <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama nasabah..." required>
        </div>

        <div class="form-group">
          <label class="form-label" for="nis">NIS / ID Identitas (Opsional)</label>
          <input type="text" id="nis" name="nis" class="form-control" placeholder="Contoh: 2026005">
        </div>

        <div class="form-group">
          <label class="form-label" for="saldo">Saldo Awal (Rp)</label>
          <input type="number" id="saldo" name="saldo" class="form-control" placeholder="0" min="0" value="0" step="1000">
        </div>

        <button type="submit" id="btnSimpan" class="btn-submit">
          <span>➕</span>
          <span>Simpan Data Nasabah</span>
        </button>
      </form>
    </div>

    <!-- Tabel Daftar Nasabah -->
    <div class="card">
      <div class="card-title">
        <span>📋</span>
        <span>Daftar Nasabah Terdaftar</span>
      </div>
      <p class="card-subtitle">Seluruh daftar akun nasabah yang telah teregistrasi dalam sistem.</p>

      <div class="search-box">
        <input type="text" id="searchInput" class="search-input" placeholder="🔍 Cari nama, RFID, atau NIS...">
      </div>

      <div class="table-container">
        <table id="tabelNasabah">
          <thead>
            <tr>
              <th>ID</th>
              <th>RFID UID</th>
              <th>Nama Nasabah</th>
              <th>NIS</th>
              <th>Saldo</th>
            </tr>
          </thead>
          <tbody id="tbodyNasabah">
            <?php if (mysqli_num_rows($nasabah_query) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($nasabah_query)): ?>
                <tr>
                  <td><?= $row['id_akun'] ?></td>
                  <td><span class="rfid-tag"><?= htmlspecialchars($row['uid_kartu'] ?? '-') ?></span></td>
                  <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                  <td><?= htmlspecialchars($row['nis'] ?? '-') ?></td>
                  <td>Rp <?= number_format($row['saldo'], 0, ',', '.') ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="empty-state">Belum ada data nasabah.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script>
    const form = document.getElementById('formTambahNasabah');
    const alertBox = document.getElementById('alertBox');
    const btnSimpan = document.getElementById('btnSimpan');
    const searchInput = document.getElementById('searchInput');
    const tbody = document.getElementById('tbodyNasabah');

    // Handle Form Submit
    form.addEventListener('submit', async function(e) {
      e.preventDefault();

      const uid_kartu = document.getElementById('uid_kartu').value.trim();
      const nama = document.getElementById('nama').value.trim();
      const nis = document.getElementById('nis').value.trim();
      const saldo = parseFloat(document.getElementById('saldo').value) || 0;

      if (!uid_kartu || !nama) {
        showAlert('ID Kartu RFID dan Nama Nasabah wajib diisi!', 'danger');
        return;
      }

      btnSimpan.disabled = true;
      btnSimpan.innerHTML = '<span>⏳</span><span>Menyimpan...</span>';

      try {
        const response = await fetch('proses_tambah_nasabah.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ uid_kartu, nama, nis, saldo })
        });

        const data = await response.json();

        if (data.sukses) {
          showAlert(`✅ ${data.pesan}`, 'success');
          form.reset();
          document.getElementById('uid_kartu').focus();

          // Tambah baris baru ke tabel secara dinamis
          tambahBarisTabel(data);
        } else {
          showAlert(`❌ ${data.pesan}`, 'danger');
        }
      } catch (err) {
        showAlert('❌ Terjadi kesalahan koneksi atau server.', 'danger');
      } finally {
        btnSimpan.disabled = false;
        btnSimpan.innerHTML = '<span>➕</span><span>Simpan Data Nasabah</span>';
      }
    });

    function showAlert(msg, type) {
      alertBox.className = `alert alert-${type}`;
      alertBox.innerHTML = msg;
      alertBox.style.display = 'block';
      setTimeout(() => {
        alertBox.style.display = 'none';
      }, 5000);
    }

    function tambahBarisTabel(data) {
      const emptyRow = tbody.querySelector('.empty-state');
      if (emptyRow) {
        tbody.innerHTML = '';
      }

      const tr = document.createElement('tr');
      const formattedSaldo = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(data.saldo);

      tr.innerHTML = `
        <td>${data.id_akun}</td>
        <td><span class="rfid-tag">${escapeHtml(data.uid_kartu)}</span></td>
        <td><strong>${escapeHtml(data.nama)}</strong></td>
        <td>${escapeHtml(document.getElementById('nis').value.trim() || '-')}</td>
        <td>${formattedSaldo}</td>
      `;
      tbody.insertBefore(tr, tbody.firstChild);
    }

    function escapeHtml(text) {
      if (!text) return '-';
      return text.replace(/[&<>"']/g, function(m) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
      });
    }

    // Filter Pencarian di Tabel
    searchInput.addEventListener('input', function() {
      const keyword = this.value.toLowerCase();
      const rows = tbody.querySelectorAll('tr');

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(keyword) ? '' : 'none';
      });
    });
  </script>
</body>
</html>
