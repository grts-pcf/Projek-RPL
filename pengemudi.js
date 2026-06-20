// Tambah Pengemudi
document.getElementById("btnTambahPengemudi").addEventListener("click", function(){

    let nama = prompt("Nama Pengemudi");
    let telepon = prompt("No Telepon");
    let sim = prompt("Jenis SIM");
    let alamat = prompt("Alamat");

    if(!nama) return;

    let tbody = document.getElementById("dataPengemudi");

    let nomor = tbody.rows.length + 1;

    let row = tbody.insertRow();

    row.innerHTML = `
        <td>${nomor}</td>
        <td>${nama}</td>
        <td>${telepon}</td>
        <td>${sim}</td>
        <td>${alamat}</td>
        <td><span class="success">Aktif</span></td>
        <td>
            <div class="action-buttons">
                <button class="btn-1 btnEdit">Edit</button>
                <button class="btn-1 btnDetail" style="background:#f59e0b;">Detail</button>
                <button class="btn-1 btnHapus" style="background:#ef4444;">Hapus</button>
            </div>
        </td>
    `;
});


// Edit, Detail, Hapus
document.addEventListener("click", function(e){

    // Edit
if(e.target.classList.contains("btnEdit")){

    let row = e.target.closest("tr");

    let namaBaru = prompt(
        "Nama Pengemudi",
        row.cells[1].innerText
    );

    let statusBaru = prompt(
        "Status (Aktif / Cuti / Tidak Aktif)",
        row.cells[5].innerText
    );

    if(namaBaru){
        row.cells[1].innerText = namaBaru;
    }

    if(statusBaru){

        if(statusBaru.toLowerCase() === "aktif"){

            row.cells[5].innerHTML =
            '<span class="success">Aktif</span>';

        }

        else if(statusBaru.toLowerCase() === "cuti"){

            row.cells[5].innerHTML =
            '<span class="pending">Cuti</span>';

        }

        else if(statusBaru.toLowerCase() === "tidak aktif"){

            row.cells[5].innerHTML =
            `<span style="
                background:#fee2e2;
                color:#991b1b;
                padding:8px 12px;
                border-radius:8px;
            ">Tidak Aktif</span>`;

        }

    }
}

    // Detail
    if(e.target.classList.contains("btnDetail")){

        let row = e.target.closest("tr");

        alert(
            "Nama : " + row.cells[1].innerText +
            "\nTelepon : " + row.cells[2].innerText +
            "\nSIM : " + row.cells[3].innerText +
            "\nAlamat : " + row.cells[4].innerText +
            "\nStatus : " + row.cells[5].innerText
        );
    }

    // Hapus
    if(e.target.classList.contains("btnHapus")){

        if(confirm("Yakin ingin menghapus pengemudi ini?")){

            e.target.closest("tr").remove();
        }
    }

});


// Search
function filterPengemudi(){

    let keyword =
        document.getElementById("searchPengemudi")
        .value.toLowerCase();

    let status =
        document.getElementById("searchStatus")
        .value.toLowerCase();

    let rows =
        document.querySelectorAll("#dataPengemudi tr");

    rows.forEach(function(row){

        let nama =
            row.cells[1].innerText.toLowerCase();

        let statusData =
            row.cells[5].innerText.toLowerCase();

        let cocokNama =
            nama.includes(keyword);

        let cocokStatus =
            status === "semua" ||
            statusData.includes(status);

        if(cocokNama && cocokStatus){
            row.style.display = "";
        }else{
            row.style.display = "none";
        }

    });
}

document.getElementById("searchPengemudi")
.addEventListener("keyup", filterPengemudi);

document.getElementById("searchStatus")
.addEventListener("change", filterPengemudi);