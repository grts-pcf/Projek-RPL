// Tombol Tambah Data
document.getElementById("btnTambah").addEventListener("click", function () {

    let nama = prompt("Masukkan Nama:");
    let unit = prompt("Masukkan Unit:");
    let telepon = prompt("Masukkan No Telepon:");
    let email = prompt("Masukkan Email:");

    if (!nama) return;

    let tbody = document.getElementById("dataPeminjam");

    let nomor = tbody.rows.length + 1;

    let row = tbody.insertRow();

    row.innerHTML = `
        <td>${nomor}</td>
        <td>${nama}</td>
        <td>${unit}</td>
        <td>${telepon}</td>
        <td>${email}</td>
        <td><span class="success">Aktif</span></td>
        <td>
            <div class="action-buttons">
                <button class="btn-1 btnEdit">Edit</button>
                <button class="btn-1 btnHapus" style="background:#ef4444">
                    Hapus
                </button>
            </div>
        </td>
    `;
});

// Edit dan Hapus
document.addEventListener("click", function (e) {

    // Hapus
    if (e.target.classList.contains("btnHapus")) {

        if (confirm("Yakin ingin menghapus data ini?")) {
            e.target.closest("tr").remove();
        }

    }

    // Edit
    if (e.target.classList.contains("btnEdit")) {

        let row = e.target.closest("tr");

        let namaBaru = prompt(
            "Edit Nama:",
            row.cells[1].innerText
        );

        if (namaBaru) {
            row.cells[1].innerText = namaBaru;
        }

    }

});

// Search Data
document.getElementById("searchInput").addEventListener("keyup", function () {

    let keyword = this.value.toLowerCase();

    let rows = document.querySelectorAll("#dataPeminjam tr");

    rows.forEach(function(row) {

        let nama = row.cells[1].innerText.toLowerCase();

        if (nama.includes(keyword)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }

    });

});