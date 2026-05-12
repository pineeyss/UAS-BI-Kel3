</main><!-- /.page-content -->

<footer style="padding:18px 28px;border-top:1px solid var(--border-soft);font-size:12px;color:var(--text-light);display:flex;justify-content:space-between;align-items:center;background:var(--surface);">
    <span>© <?= date('Y') ?> <strong>SIJALAN</strong> — Sistem Informasi Manajemen Perbaikan Jalan</span>
    <span>MIS &amp; Business Intelligence</span>
</footer>
</div><!-- /.main-wrapper -->

<!-- Mobile overlay -->
<div id="sidebarOverlay"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:199;"
    onclick="document.getElementById('sidebar').classList.remove('open');this.style.display='none';">
</div>

<script>
    /* Show overlay when sidebar opens on mobile */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const observer = new MutationObserver(() => {
        overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
    });
    observer.observe(sidebar, {
        attributes: true,
        attributeFilter: ['class']
    });
</script>

<?php if (!empty($extraScript)) echo $extraScript; ?>
</body>

</html>