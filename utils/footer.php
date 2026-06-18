<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // SEGURO 1: Solo ejecutar DataTables si la tabla realmente existe en esta pantalla
            if ($('.tabla-datatable').length > 0) {
                $('.tabla-datatable').DataTable({
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                    },
                    "pageLength": 10, 
                    "lengthMenu": [5, 10, 25, 50], 
                    "ordering": true, 
                    "info": true 
                });
            }
        });

        // SEGURO 2: Solo agregar el clic del menú si el botón existe
        let btnSidebar = document.getElementById('sidebarToggle');
        if (btnSidebar) {
            btnSidebar.addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('show');
            });
        }
    </script>
</body>
</html>