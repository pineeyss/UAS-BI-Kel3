</main><!-- /.main-content -->

<!-- ===================== MODAL ===================== -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>
<div class="modal" id="modal">
    <div class="modal-head">
        <h3 id="modalTitle">Detail</h3>
        <button class="modal-close" onclick="closeModal()" title="Tutup">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" />
            </svg>
        </button>
    </div>
    <div class="modal-body" id="modalBody"></div>
</div>

<!-- ===================== TOAST ===================== -->
<div class="toast" id="toast"></div>

<script>
    /* Global vars injected from PHP */
    const APP_URL = '<?= APP_URL ?>';
    const CSRF = '<?= csrf() ?>';
    const IS_ADMIN = <?= isAdmin() ? 'true' : 'false' ?>;

    /* Modal helpers */
    function openModal(title, bodyHTML) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalBody').innerHTML = bodyHTML;
        document.getElementById('modalOverlay').classList.add('show');
        document.getElementById('modal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('show');
        document.getElementById('modal').classList.remove('show');
        document.body.style.overflow = '';
    }

    /* Toast */
    function showToast(msg, type = 'success') {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = `toast show ${type}`;
        clearTimeout(t._timer);
        t._timer = setTimeout(() => {
            t.className = 'toast';
        }, 3200);
    }

    /* Badge helpers (used in app.js too) */
    function statusBadge(status) {
        const map = {
            'Pending': ['badge-pending', 'Pending'],
            'Diproses': ['badge-diproses', 'Diproses'],
            'Selesai': ['badge-selesai', 'Selesai'],
            'Ditolak': ['badge-ditolak', 'Ditolak'],
        };
        const [cls, label] = map[status] ?? ['badge-pending', status];
        return `<span class="badge ${cls}">${label}</span>`;
    }

    function tingkatBadge(t) {
        const map = {
            'Ringan': 'badge-ringan',
            'Sedang': 'badge-sedang',
            'Berat': 'badge-berat'
        };
        return `<span class="badge ${map[t] ?? ''}">${t}</span>`;
    }

    /* Close modal on ESC */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });
</script>
<script src="<?= APP_URL ?>/js/app.js"></script>
</body>

</html>