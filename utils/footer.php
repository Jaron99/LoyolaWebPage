<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            if ($('.tabla-datatable').length > 0) {
                $('.tabla-datatable').DataTable({
                    "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" },
                    "pageLength": 10, 
                    "lengthMenu": [5, 10, 25, 50], 
                    "ordering": true, 
                    "info": true 
                });
            }
        });

        // Script del menú lateral
        let btnSidebar = document.getElementById('sidebarToggle');
        if (btnSidebar) {
            btnSidebar.addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('show');
            });
        }
    </script>

    <?php
$swal_title = "";
$swal_text  = "";
$swal_icon  = "";

if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'ajustes_guardados':
            $swal_title = "¡Éxito!";
            $swal_text  = "La configuración del sistema se guardó correctamente.";
            $swal_icon  = "success";
            break;
        case 'restauracion_exitosa':
            $swal_title = "¡Sistema Restaurado!";
            $swal_text  = "La base de datos se restauró con éxito desde el archivo de respaldo.";
            $swal_icon  = "success";
            break;
        case 'error_restauracion':
            $swal_title = "Error Crítico";
            $swal_text  = "El archivo SQL está corrupto o no se pudo procesar.";
            $swal_icon  = "error";
            break;
        case 'archivo_invalido':
            $swal_title = "Formato Incorrecto";
            $swal_text  = "Por favor, suba únicamente archivos con extensión .sql";
            $swal_icon  = "warning";
            break;
    }
}

if (isset($_SESSION['error'])) {
    $swal_title = "¡Atención!";
    $swal_text  = $_SESSION['error'];
    $swal_icon  = "error";
    unset($_SESSION['error']);
}

// ✅ json_encode escapa automáticamente todo — elimina XSS por completo
if ($swal_title !== "") {
    $js_title = json_encode($swal_title);
    $js_text  = json_encode($swal_text);
    $js_icon  = json_encode($swal_icon);
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: {$js_title},
                text: {$js_text},
                icon: {$js_icon},
                confirmButtonColor: '#0d6efd',
                customClass: { popup: 'rounded-4 shadow-lg' }
            });
        });
    </script>";
}
?>
</body>
</html>