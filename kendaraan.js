// Tambah Kendaraan
document.getElementById("btnTambahKendaraan").addEventListener("click", function(){

    let merk = prompt("Masukkan Merk Kendaraan");
    let tahun = prompt("Masukkan Tahun");
    let plat = prompt("Masukkan Plat Nomor");
    let pajak = prompt("Masukkan Tanggal Pajak");

    if(merk && tahun && plat && pajak){

        let tbody = document.getElementById("dataKendaraan");

        let nomor = tbody.rows.length + 1;

        let row = tbody.insertRow();

        row.innerHTML = `
            <td>${nomor}</td>
            <td>${merk}</td>
            <td>${tahun}</td>
            <td>${plat}</td>
            <td>${pajak}</td>
            <td>
                <span class="success">
                    Tersedia
                </span>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn-1 btnEdit">Edit</button>
                    <button class="btn-1 btnDetail" style="background:#f59e0b;">Detail</button>
                    <button class="btn-1 btnService" style="background:#ef4444;">Services</button>
                </div>
            </td>
        `;
    }
});

// Semua aksi tombol
document.addEventListener("click", function(e){

    // Detail
    if(e.target.classList.contains("btnDetail")){

        let row = e.target.closest("tr");

        alert(
            "Merk : " + row.cells[1].innerText +
            "\nTahun : " + row.cells[2].innerText +
            "\nPlat : " + row.cells[3].innerText +
            "\nPajak : " + row.cells[4].innerText +
            "\nStatus : " + row.cells[5].innerText
        );
    }

    // Edit
    if(e.target.classList.contains("btnEdit")){

        let row = e.target.closest("tr");

        let merk = prompt("Edit Merk", row.cells[1].innerText);

        if(merk){
            row.cells[1].innerText = merk;
        }
    }

    // Service
    if(e.target.classList.contains("btnService")){

        let row = e.target.closest("tr");

        row.cells[5].innerHTML =
        `<span style="
            background:#fee2e2;
            color:#991b1b;
            padding:8px 12px;
            border-radius:8px;
        ">
            Maintenance
        </span>`;

        alert("Status kendaraan diubah menjadi Maintenance");
    }

});

function filterKendaraan() {

    let keyword = document.getElementById("searchKendaraan").value.toLowerCase();
    let status = document.getElementById("searchStatus").value.toLowerCase();

    let rows = document.querySelectorAll("#dataKendaraan tr");

    rows.forEach(function(row){

        let nama = row.cells[1].innerText.toLowerCase();
        let statusKendaraan = row.cells[5].innerText.toLowerCase();

        let cocokNama = nama.includes(keyword);

        let cocokStatus =
            status === "semua" ||
            status === "" ||
            statusKendaraan.includes(status);

        if(cocokNama && cocokStatus){
            row.style.display = "";
        }else{
            row.style.display = "none";
        }

    });
}

document.getElementById("searchKendaraan")
.addEventListener("keyup", filterKendaraan);

document.getElementById("searchStatus")
.addEventListener("change", filterKendaraan);