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
