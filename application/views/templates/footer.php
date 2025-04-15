</div>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <!-- Sweet Alert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.all.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        $(document).ready(function() {
            // Mulai dengan menyembunyikan sidebar di perangkat mobile
            if ($(window).width() < 992) {
                $('#sidebar').removeClass('show');
                $('main').removeClass('sidebar-active');
            } else {
                $('#sidebar').addClass('show');
                $('main').addClass('sidebar-active');
            }
            
            // Toggle sidebar on click
            $('.toggle-sidebar').click(function() {
                $('#sidebar').toggleClass('show');
                $('main').toggleClass('sidebar-active');
            });
            
            // Hide sidebar when clicking anywhere outside on mobile
            $(document).click(function(event) {
                if ($(window).width() < 992) {
                    if (!$(event.target).closest('#sidebar').length && 
                        !$(event.target).closest('.toggle-sidebar').length && 
                        $('#sidebar').hasClass('show')) {
                        $('#sidebar').removeClass('show');
                        $('main').removeClass('sidebar-active');
                    }
                }
            });
            
            // Update sidebar state on window resize
            $(window).resize(function() {
                if ($(window).width() >= 992) {
                    $('#sidebar').addClass('show');
                    $('main').addClass('sidebar-active');
                } else {
                    $('#sidebar').removeClass('show');
                    $('main').removeClass('sidebar-active');
                }
            });
        });
    </script>
    
    <!-- Flash Message -->
    <?php if ($this->session->flashdata('message')) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?= $this->session->flashdata('message') ?>',
            timer: 3000,
            timerProgressBar: true
        });
    </script>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('error')) : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= $this->session->flashdata('error') ?>',
            timer: 3000,
            timerProgressBar: true
        });
    </script>
    <?php endif; ?>
    
    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true
            });
        });
    </script>
</body>
</html>