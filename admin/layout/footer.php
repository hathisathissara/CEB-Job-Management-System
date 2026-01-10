</div> <!-- End content-body -->
</div> <!-- End main-content -->

<!-- Loader Script -->
<script>
    window.addEventListener('load', function() {
        var loader = document.getElementById('loader-wrapper');
        setTimeout(function() {
            loader.style.opacity = '0';
            setTimeout(function() {
                loader.style.display = 'none';
                document.body.classList.remove('loading');
            }, 500);
        }, 300);
    });

    function toggleMenu() {
        document.getElementById('sidebar').classList.toggle('active');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>