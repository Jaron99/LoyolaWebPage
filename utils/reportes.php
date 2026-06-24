<?php
// Aseguramos que solo usuarios logueados puedan ver esto
if (!isset($_SESSION['rol'])) {
    exit("Acceso denegado.");
}
$rol = $_SESSION['rol'];

require_once __DIR__ . '/../models/conexion.model.php';
$conexion = Conexion::connect();
?>

<div class="tab-pane fade show active" id="vista-reportes">

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Centro de Reportes Oficiales</h2>
        <p class="text-muted">Generación y descarga de documentos académicos en formato PDF.</p>
    </div>

    <?php if ($rol === 'admin'): ?>
        <div class="row g-4">

            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-top: 5px solid #0dcaf0;">
                    <div class="card-body p-4 text-center">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-building fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Reporte General</h5>
                        <p class="text-muted small">Genera un PDF completo con las calificaciones de todo el colegio. <br> </p>
                        <a href="../controllers/reportes.controller.php?tipo=general" target="_blank" class="btn btn-outline-info w-100 mt-2 fw-bold">
                            <i class="bi bi-download"></i> Generar PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-top: 5px solid #6f42c1;">
                    <div class="card-body p-4 text-center">
                        <div class="bg-purple bg-opacity-10 text-purple rounded-circle d-inline-flex p-3 mb-3" style="color: #6f42c1; background-color: rgba(111,66,193,0.1);">
                            <i class="bi bi-door-open fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Reporte por Sección</h5>
                        <p class="text-muted small">Selecciona un grado y sección para generar su acta de notas.</p>
                        <form action="../controllers/reportes.controller.php" method="GET" target="_blank">
                            <input type="hidden" name="tipo" value="seccion">
                            <select name="id_seccion" class="form-select mb-3" required>
                                <option value="">Seleccione una sección...</option>
                                <?php
                                // Usamos el nombre correcto de la vista (vw_grados_secciones) 
                                // y aseguramos que solo traiga grados que ya tienen una sección asignada
                                $sql = "SELECT id_seccion, CONCAT(nombre_grad, ' ', nombre_sec) AS nombre 
                                        FROM vw_grados_secciones 
                                        WHERE id_seccion IS NOT NULL";

                                $res = $conexion->query($sql);

                                if ($res && $res->num_rows > 0) {
                                    while ($s = $res->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($s['id_seccion']) . "'>" . htmlspecialchars($s['nombre']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No hay secciones disponibles</option>";
                                }
                                ?>
                            </select>
                            <button class="btn btn-outline-success w-100 fw-bold"><i class="bi bi-download"></i> Generar PDF</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-top: 5px solid #198754;">
                    <div class="card-body p-4 text-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-person-badge fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Boletín Individual</h5>
                        <p class="text-muted small">Genera el boletín oficial de un estudiante específico mediante su Código.</p>
                        <form action="../controllers/reportes.controller.php" method="GET" target="_blank">
                            <input type="hidden" name="tipo" value="individual">
                            <input type="text" name="codigo_mined" class="form-control mb-3" placeholder="Ingrese el Código MINED..." required>
                            <button type="submit" class="btn btn-outline-success w-100 fw-bold">
                                <i class="bi bi-download"></i> Generar PDF
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <?php if ($rol === 'docente'): ?>
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-top: 5px solid #6f42c1;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-purple bg-opacity-10 text-purple rounded-circle p-3 me-3" style="color: #6f42c1; background-color: rgba(111,66,193,0.1);">
                                <i class="bi bi-journal-text fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-0">Reporte de Mis Secciones</h5>
                        </div>
                        <p class="text-muted">Descargue el acta oficial de calificaciones de las asignaturas que usted imparte.</p>

                        <form action="../controllers/reportes.controller.php" method="GET" target="_blank">
                            <input type="hidden" name="tipo" value="seccion_materia">
                            <label class="form-label fw-bold">Seleccione su asignatura/sección:</label>
                            <select name="datos_asignatura" class="form-select mb-4" required>
                                <option value="">Seleccione una opción...</option>
                                <?php
                                // Obtenemos el ID del profesor desde la sesión (ej. DOC-001)
                                $id_profesor = $_SESSION['id_referencia'] ?? '';

                                // Consultamos las asignaturas y secciones que le pertenecen a este profesor
                                $sql_docente = "
                                    SELECT s.id_seccion, a.nombre_asig, CONCAT(g.nombre_grad, ' ', s.nombre_sec, ' - ', a.nombre_asig) as nombre_opcion 
                                    FROM asignatura a 
                                    JOIN grado g ON a.id_grado = g.id_grado 
                                    JOIN seccion s ON g.id_grado = s.id_grado 
                                    WHERE a.id_profesor = ?
                                    ORDER BY g.id_grado, s.nombre_sec, a.nombre_asig";

                                $stmtDoc = $conexion->prepare($sql_docente);
                                $stmtDoc->bind_param("s", $id_profesor);
                                $stmtDoc->execute();
                                $resDoc = $stmtDoc->get_result();

                                if ($resDoc && $resDoc->num_rows > 0) {
                                    while ($doc_sec = $resDoc->fetch_assoc()) {
                                        // Guardamos el id_seccion y el nombre de la asignatura separados por un "|"
                                        $valor = $doc_sec['id_seccion'] . '|' . $doc_sec['nombre_asig'];
                                        echo "<option value='" . htmlspecialchars($valor) . "'>" . htmlspecialchars($doc_sec['nombre_opcion']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No tiene asignaturas asignadas</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-outline-purple w-100 fw-bold p-2" style="color: #6f42c1; border-color: #6f42c1; transition: 0.3s;" onmouseover="this.style.backgroundColor='#6f42c1'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#6f42c1';">
                                <i class="bi bi-file-earmark-pdf-fill"></i> Descargar Acta
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <?php if ($rol === 'estudiante'): ?>
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-top: 5px solid #dc3545;">
                    <div class="card-body p-4 text-center">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-mortarboard-fill fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Mi Boletín Oficial</h4>
                        <p class="text-muted mb-4">Descarga tu historial de calificaciones y notas en formato PDF, listo para imprimir.</p>
                        <a href="../controllers/reportes.controller.php?tipo=mi_boletin" target="_blank" class="btn btn-danger btn-lg w-100 fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-cloud-arrow-down-fill me-2"></i> Descargar Historial
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>