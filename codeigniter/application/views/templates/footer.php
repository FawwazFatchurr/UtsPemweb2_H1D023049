</div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-dark text-white fixed-bottom">
        <div class="container text-center">
            <span>&copy; <?= date('Y') ?> Inventory Management System</span>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search..."
                }
            });
            
            // Initialize all tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Set minimum date for date inputs
            $('.min-today').attr('min', function() {
                return new Date().toISOString().split('T')[0];
            });
            
            // Item selector for loan form
            $('#item_id').change(function() {
                let stockElement = $('#available_stock');
                let selectedOption = $(this).find('option:selected');
                let stock = selectedOption.data('stock');
                
                if (stock) {
                    stockElement.html('<div class="alert alert-info mt-2">Available Stock: <strong>' + stock + '</strong></div>');
                    $('#quantity').attr('max', stock);
                } else {
                    stockElement.html('');
                    $('#quantity').removeAttr('max');
                }
            });
            
            // Update loan status automatically
            function updateLoanStatus() {
                $('.loan-status').each(function() {
                    const due_date = new Date($(this).data('due-date'));
                    const today = new Date();
                    const status = $(this).data('status');
                    
                    // Only update for items still borrowed
                    if (status === 'borrowed' && due_date < today) {
                        $(this).removeClass('badge-borrowed').addClass('badge-overdue');
                        $(this).text('Overdue');
                    }
                });
            }
            
            // Run once on page load
            updateLoanStatus();
        });
    </script>
</body>
</html>