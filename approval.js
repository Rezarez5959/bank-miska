function setujui(id_piutang) {
  var konfirmasi = confirm("Setujui pinjaman ini? Saldo akan langsung dicairkan.");
  if (!konfirmasi) {
    return;
  }

  fetch("proses_approval_pinjaman.php", {
    method: "POST",
    body: JSON.stringify({
      id_piutang: id_piutang,
      aksi: "setuju"
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

      alert("Pinjaman disetujui dan dicairkan. Jadwal angsuran sudah dibuat.");
      location.reload(); // muat ulang halaman supaya daftar pengajuan ter-update
    });
}


function tolak(id_piutang) {
  var konfirmasi = confirm("Tolak pengajuan pinjaman ini?");
  if (!konfirmasi) {
    return;
  }

  fetch("proses_approval_pinjaman.php", {
    method: "POST",
    body: JSON.stringify({
      id_piutang: id_piutang,
      aksi: "tolak"
    })
  })
    .then(function(response) {
      return response.json();
    })
    .then(function(data) {

      alert("Pengajuan pinjaman ditolak.");
      location.reload();
    });
}
