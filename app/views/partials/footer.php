        </div><!-- .content-area -->
        </div><!-- .main-content -->
        </div><!-- .wrapper -->

        <script>
            document.getElementById('toggleSidebar')?.addEventListener('click', () => {
                document.getElementById('sidebar').classList.toggle('collapsed');
                document.querySelector('.main-content').classList.toggle('expanded');
            });
            // Auto-close flash after 4s
            setTimeout(() => {
                document.getElementById('flashMsg')?.remove();
            }, 4000);
        </script>
        <?= $extraScript ?? '' ?>
        </body>

        </html>