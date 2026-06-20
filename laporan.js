// Generate Laporan
document.getElementById("btnGenerate")
.addEventListener("click", function(){

    let tglAwal =
        document.getElementById("tglAwal").value;

    let tglAkhir =
        document.getElementById("tglAkhir").value;

    let status =
        document.getElementById("statusFilter")
        .value.toLowerCase();

    let rows =
        document.querySelectorAll("#dataLaporan tr");

    rows.forEach(function(row){

        let tanggal =
            row.cells[4].getAttribute("data-date");

        let statusData =
            row.cells[6].innerText.toLowerCase();

        let cocokStatus =
            status === "semua" ||
            statusData.includes(status);

        let cocokTanggal = true;

        if(tglAwal && tanggal < tglAwal){
            cocokTanggal = false;
        }

        if(tglAkhir && tanggal > tglAkhir){
            cocokTanggal = false;
        }

        if(cocokStatus && cocokTanggal){
            row.style.display = "";
        }else{
            row.style.display = "none";
        }

    });

    alert("Laporan berhasil dibuat");

});


// Export Excel
document.getElementById("btnExportExcel")
.addEventListener("click", function(){

    let table =
        document.querySelector("table").outerHTML;

    let dataType =
        "application/vnd.ms-excel";

    let a =
        document.createElement("a");

    a.href =
        "data:" + dataType +
        ", " + encodeURIComponent(table);

    a.download =
        "laporan_peminjaman.xls";

    a.click();

});