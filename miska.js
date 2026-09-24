// Menyimpan data nasabah yang sedang aktif dicari
var nasabahAktif = null;


// ======================================================
// AKSI 1: CARI NASABAH
// ======================================================
function cariNasabah() {
  var uid = document.getElementById("uid").value;

  fetch("lookup_kartu.php?uid=" + uid)
    .then(function(response) {
      return response.json();
    })
    .then(function(data) {

      if (data.sukses == false) {
        document.getElementById("info-nasabah").innerHTML = data.pesan;
        nasabahAktif = null;
        return;
      }

      // Simpan data nasabah supaya bisa dipakai lagi pas Top Up / Tarik Dana
      nasabahAktif = data;

      document.getElementById("info-nasabah").innerHTML =
        "Nama: " + data.nama + " (" + data.nis + ") - Saldo: Rp " + data.saldo;
    });
}


// ======================================================
// AKSI 2: TOP UP
// ======================================================
function prosesTopup() {
  if (nasabahAktif == null) {
    alert("Cari nasabah dulu sebelum top up");
    return;
  }

  var nominal = document.getElementById("nominal-topup").value;

  fetch("proses_topup.php", {
    method: "POST",
    body: JSON.stringify({
      id_akun: nasabahAktif.id_akun,
      nominal: nominal
    })
  })
    .then(function(response) {
      return response.json();
    })
    .then(function(data) {

      document.getElementById("pesan").innerHTML =
        "Top up berhasil. Saldo sekarang: Rp " + data.saldo_akhir;

      // Update saldo yang tampil di info nasabah juga
      nasabahAktif.saldo = data.saldo_akhir;
      document.getElementById("info-nasabah").innerHTML =
        "Nama: " + nasabahAktif.nama + " (" + nasabahAktif.nis + ") - Saldo: Rp " + nasabahAktif.saldo;

      document.getElementById("nominal-topup").value = "";
    });
}


// ======================================================
// AKSI 3: TARIK DANA
// ======================================================
function prosesTarik() {
  if (nasabahAktif == null) {
    alert("Cari nasabah dulu sebelum tarik dana");
    return;
  }

  var nominal = document.getElementById("nominal-tarik").value;

  fetch("proses_tarik.php", {
    method: "POST",
    body: JSON.stringify({
      id_akun: nasabahAktif.id_akun,
      nominal: nominal
    })
  })
    .then(function(response) {
      return response.json();
    })
    .then(function(data) {

      if (data.sukses == false) {
        document.getElementById("pesan").innerHTML = data.pesan;
        return;
      }

      document.getElementById("pesan").innerHTML =
        "Tarik dana berhasil. Saldo sekarang: Rp " + data.saldo_akhir;

      nasabahAktif.saldo = data.saldo_akhir;
      document.getElementById("info-nasabah").innerHTML =
        "Nama: " + nasabahAktif.nama + " (" + nasabahAktif.nis + ") - Saldo: Rp " + nasabahAktif.saldo;

      document.getElementById("nominal-tarik").value = "";
    });
}


// ======================================================
// AKSI 4: AJUKAN PINJAMAN
// ======================================================
function ajukanPinjaman() {
  if (nasabahAktif == null) {
    alert("Cari nasabah dulu sebelum ajukan pinjaman");
    return;
  }

  var plafon = document.getElementById("plafon-pinjaman").value;
  var bunga = document.getElementById("bunga-pinjaman").value;
  var tenor = document.getElementById("tenor-pinjaman").value;

  fetch("proses_ajukan_pinjaman.php", {
    method: "POST",
    body: JSON.stringify({
      id_akun: nasabahAktif.id_akun,
      plafon: plafon,
      bunga: bunga,
      tenor: tenor
    })
  })
    .then(function(response) {
      return response.json();
    })
    .then(function(data) {

      if (data.sukses == false) {
        document.getElementById("pesan").innerHTML = data.pesan;
        return;
      }

      document.getElementById("pesan").innerHTML =
        "Pengajuan pinjaman berhasil dicatat. Status: menunggu persetujuan admin. " +
        "Total yang harus dibayar nanti: Rp " + data.total_harus_dibayar;

      document.getElementById("plafon-pinjaman").value = "";
      document.getElementById("bunga-pinjaman").value = "";
      document.getElementById("tenor-pinjaman").value = "";
    });
}
