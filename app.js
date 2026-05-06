document.addEventListener("DOMContentLoaded", function() {
    const fileCSV = "data_kerusakan_jalan_clean.csv";

    Papa.parse(fileCSV, {
        download: true,
        header: true,
        complete: function(res) {
            const data = res.data.filter(r => r.image_id);
            renderMIS(data);
        }
    });
});

function renderMIS(data) {
    const tbody = document.getElementById("isiTabel");
    const totalH2 = document.getElementById("txtTotal");
    const urgentH2 = document.getElementById("txtUrgent");

    totalH2.innerText = data.length.toLocaleString();
    let urgentCount = 0;

    data.slice(0, 25).forEach((item, index) => {
        const lubang = parseInt(item.num_potholes);
        let baris = document.createElement("tr");
        
        let labelPrioritas = "Normal";
        let priorityClass = "p-normal";

        if (lubang > 5) {
            labelPrioritas = "URGENT";
            priorityClass = "p-urgent";
            urgentCount++;
        }

        baris.innerHTML = `
            <td><span class="img-id">${item.image_id}</span></td>
            <td><span class="pothole-count">${lubang} Titik</span></td>
            <td class="coord-text">${item.x} , ${item.y}</td>
            <td><span class="priority-tag ${priorityClass}">${labelPrioritas}</span></td>
            <td>
                <select class="status-select" onchange="changeStatusColor(this)">
                    <option value="perlu_ditinjau" selected>Perlu Ditinjau</option>
                    <option value="proses">Proses Perbaikan</option>
                    <option value="selesai">Selesai</option>
                </select>
            </td>
        `;
        tbody.appendChild(baris);
    });

    urgentH2.innerText = data.filter(d => parseInt(d.num_potholes) > 5).length.toLocaleString();
}

// Fungsi estetik untuk mengubah warna select saat diganti
function changeStatusColor(select) {
    if(select.value === 'selesai') select.style.color = '#2ecc71';
    else if(select.value === 'proses') select.style.color = '#3498db';
    else select.style.color = '#7f8c8d';
}