</div> <!-- End content-body -->
</div> <!-- End main-content -->

<!-- Scripts -->
<script>
    /* ── Loader ── */
    window.addEventListener('load', function () {
        var loader = document.getElementById('loader-wrapper');
        setTimeout(function () {
            loader.style.opacity = '0';
            setTimeout(function () {
                loader.style.display = 'none';
                document.body.classList.remove('loading');
            }, 500);
        }, 300);
    });

    /* ── Native App Drawer Sidebar Toggle ── */
    function toggleMenu() {
        var sidebar  = document.getElementById('sidebar');
        var overlay  = document.getElementById('sidebarOverlay');
        var isOpen   = sidebar.classList.contains('active');

        if (isOpen) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = ''; // Restore scroll
        } else {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Lock background scroll like real app
        }
    }

    /* ── Close sidebar when tapping the overlay ── */
    document.addEventListener('DOMContentLoaded', function() {
        var overlay = document.getElementById('sidebarOverlay');
        if (overlay) {
            overlay.addEventListener('click', toggleMenu);
        }
    });

    /* ── Nav Group Accordion ── */
    function toggleGroup(groupId) {
        var group  = document.getElementById(groupId);
        var toggle = group ? group.querySelector('.nav-group-toggle') : null;
        var sub    = document.getElementById('sub-' + groupId);
        if (!toggle || !sub) return;
        toggle.classList.toggle('open');
        sub.classList.toggle('open');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html