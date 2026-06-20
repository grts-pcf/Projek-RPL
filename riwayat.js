// Export PDF
document.getElementById("btnExport").addEventListener("click", function () {
    window.print();
});

// Semua tombol
document.addEventListener("click", function(e){

    // Detail
    if(e.target.classList.contains("btnDetail")){

        let row = e.target.closest("tr");

        alert(
            "Nama : " + row.cells[1].innerText +
            "\nKendaraan : " + row.cells[2].innerText +
            "\nPengemudi : " + row.cells[3].innerText +
            "\nTujuan : " + row.cells[6].innerText
        );
    }

    // Review
    if(e.target.classList.contains("btnReview")){

        let row = e.target.closest("tr");

        let statusBaru = prompt(
            "Masukkan status:\nDisetujui / Pending / Ditolak"
        );

        if(statusBaru){

            if(statusBaru.toLowerCase() === "disetujui"){
                row.cells[7].innerHTML =
                '<span class="success">Disetujui</span>';
            }

            else if(statusBaru.toLowerCase() === "pending"){
                row.cells[7].innerHTML =
                '<span class="pending">Pending</span>';
            }

            else if(statusBaru.toLowerCase() === "ditolak"){
                row.cells[7].innerHTML =
                `<span style="
                    background:#fee2e2;
                    color:#991b1b;
                    padding:8px 12px;
                    border-radius:8px;
                ">Ditolak</span>`;
            }
        }
    }

    // Hapus
    if(e.target.classList.contains("btnHapus")){

        if(confirm("Yakin ingin menghapus data?")){
            e.target.closest("tr").remove();
        }

    }

});

// FILTER DATA
function filterData() {

    let nama = document.getElementById("searchNama").value.toLowerCase();
    let status = document.getElementById("searchStatus").value.toLowerCase();
    let tanggal = document.getElementById("searchTanggal").value;

    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(function(row){

        let namaData = row.cells[1].innerText.toLowerCase();
        let statusData = row.cells[7].innerText.toLowerCase();
        let tanggalData = row.cells[4].getAttribute("data-date");

        let cocokNama =
            namaData.includes(nama);

        let cocokStatus =
            status === "semua" ||
            status === "" ||
            statusData.includes(status);

        let cocokTanggal =
            tanggal === "" ||
            tanggalData === tanggal;

        if(cocokNama && cocokStatus && cocokTanggal){
            row.style.display = "";
        }
        else{
            row.style.display = "none";
        }

    });

}

// Event Search
document.getElementById("searchNama")
.addEventListener("keyup", filterData);

document.getElementById("searchStatus")
.addEventListener("change", filterData);

document.getElementById("searchTanggal")
.addEventListener("change", filterData);