// Nexus Admin JS

// Table live search
document.querySelectorAll('.table-search').forEach(input => {
    input.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        const tbodyId = this.dataset.target;
        const tbody = tbodyId ? document.querySelector(tbodyId) : document.querySelector('tbody');
        if (!tbody) return;
        tbody.querySelectorAll('tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
});

// Confirm delete
let pendingDeleteForm = null;

function cancelDelete() {
    document.getElementById('confirm-overlay').classList.remove('open');
    document.body.style.overflow = '';
    pendingDeleteForm = null;
}

function proceedDelete() {
    if (pendingDeleteForm) pendingDeleteForm.submit();
    cancelDelete();
}

document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        pendingDeleteForm = this;
        document.getElementById('confirm-item-name').textContent = this.dataset.title || 'this item';
        document.getElementById('confirm-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    });
});

// Auto-hide alerts
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity 0.4s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 400);
    }, 4000);
});

// ESC closes confirm
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cancelDelete();
});

// CSV Export Utility
function exportTableToCSV(filename, tableSelector = 'table') {
    const table = document.querySelector(tableSelector);
    if (!table) {
        alert("No table found to export.");
        return;
    }

    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            // Get text content, remove newlines/extra spaces, and handle quotes inside the string
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').trim();
            // Escape double-quotes
            data = data.replace(/"/g, '""');
            // If data contains comma, quote, or newline, wrap it in quotes
            if (data.search(/("|,|\n)/g) >= 0) {
                data = '"' + data + '"';
            }
            // Skip the "Actions" column if it's the last one for cleaner export
            if (i === 0 && data.toLowerCase().includes('action')) {
                continue;
            }
            if (i > 0 && j === cols.length - 1 && rows[0].querySelectorAll('th')[j]?.innerText.toLowerCase().includes('action')) {
                continue;
            }
            row.push(data);
        }
        if (row.length > 0) csv.push(row.join(','));
    }

    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    let csvFile;
    let downloadLink;

    csvFile = new Blob([csv], {type: "text/csv"});
    downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
