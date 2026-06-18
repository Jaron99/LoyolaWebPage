<?php
// Llamamos al controlador de evaluaciones
include_once __DIR__ . "/../controllers/evaluacion.controller.php";
?>

<div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'evaluacion') ? 'show active' : ''; ?>" id="vista-evaluacion">

    <!-- Encabezado con Botón de Regresar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Registro de Notas</h2>
            <p class="text-muted mb-0">
                Asignatura: <strong class="text-primary"><?php echo htmlspecialchars($nombre_asig); ?></strong>
            </p>
        </div>
        <a href="?tab=calificaciones" class="btn btn-outline-secondary rounded-pill fw-bold shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver a mis clases
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-0">
            <form action="../controllers/evaluacion.controller.php" method="POST">
                <input type="hidden" name="action" value="guardar_notas">
                <input type="hidden" name="id_asig" value="<?php echo htmlspecialchars($id_asig); ?>">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center px-2 py-3" style="width: 40px;">#</th>
                                <th class="py-3">Estudiante</th>
                                <th class="text-center py-3" style="width: 130px;">I Parcial</th>
                                <th class="text-center py-3" style="width: 130px;">II Parcial</th>
                                <th class="text-center py-3 bg-info bg-opacity-10 text-info-emphasis" style="width: 90px;">I Sem.</th>
                                <th class="text-center py-3" style="width: 130px;">III Parcial</th>
                                <th class="text-center py-3" style="width: 130px;">IV Parcial</th>
                                <th class="text-center py-3 bg-info bg-opacity-10 text-info-emphasis" style="width: 90px;">II Sem.</th>
                                <th class="text-center py-3 bg-primary bg-opacity-10 text-primary" style="width: 100px;">N. Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($alumnos)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="bi bi-people-fill fs-1 d-block mb-2 opacity-50"></i>
                                        No hay alumnos matriculados en esta sección.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $contador = 1;
                                foreach ($alumnos as $row):
                                    // Rescatamos notas individuales para el cálculo inicial en PHP
                                    $n1 = isset($row['notas']['I Parcial']) && is_numeric($row['notas']['I Parcial']) ? (float)$row['notas']['I Parcial'] : null;
                                    $n2 = isset($row['notas']['II Parcial']) && is_numeric($row['notas']['II Parcial']) ? (float)$row['notas']['II Parcial'] : null;
                                    $n3 = isset($row['notas']['III Parcial']) && is_numeric($row['notas']['III Parcial']) ? (float)$row['notas']['III Parcial'] : null;
                                    $n4 = isset($row['notas']['IV Parcial']) && is_numeric($row['notas']['IV Parcial']) ? (float)$row['notas']['IV Parcial'] : null;

                                    // Cálculo oficial MINED: Promedios redondeados
                                    $sem1 = ($n1 !== null && $n2 !== null) ? round(($n1 + $n2) / 2) : '-';
                                    $sem2 = ($n3 !== null && $n4 !== null) ? round(($n3 + $n4) / 2) : '-';
                                    $final = ($sem1 !== '-' && $sem2 !== '-') ? round(($sem1 + $sem2) / 2) : '-';
                                ?>
                                    <tr class="fila-alumno">
                                        <td class="text-center text-muted fw-bold px-2"><?php echo $contador++; ?></td>
                                        <td class="fw-bold text-dark text-nowrap" style="font-size: 0.9rem;">
                                            <?php echo htmlspecialchars($row['nombres'] . " " . $row['apellidos']); ?>
                                        </td>

                                        <td class="text-center px-1">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <input type="number" name="notas[I Parcial][<?php echo $row['id_matricula']; ?>]"
                                                    class="form-control text-center fw-bold border-2 nota-input p1"
                                                    value="<?php echo htmlspecialchars($row['notas']['I Parcial'] ?? ''); ?>"
                                                    min="0" max="100" step="0.01" placeholder="-" <?php echo $parciales_status['I Parcial'] ? '' : 'readonly tabindex="-1" style="background-color: #e9ecef; pointer-events:none;"'; ?>>
                                                <span class="badge bg-secondary cualitativa-span shadow-sm" style="min-width: 35px; font-size: 0.75rem;">-</span>
                                            </div>
                                        </td>

                                        <td class="text-center px-1">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <input type="number" name="notas[II Parcial][<?php echo $row['id_matricula']; ?>]"
                                                    class="form-control text-center fw-bold border-2 nota-input p2"
                                                    value="<?php echo htmlspecialchars($row['notas']['II Parcial'] ?? ''); ?>"
                                                    min="0" max="100" step="0.01" placeholder="-" <?php echo $parciales_status['II Parcial'] ? '' : 'readonly tabindex="-1" style="background-color: #e9ecef; pointer-events:none;"'; ?>>
                                                <span class="badge bg-secondary cualitativa-span shadow-sm" style="min-width: 35px; font-size: 0.75rem;">-</span>
                                            </div>
                                        </td>

                                        <td class="text-center px-2 bg-info bg-opacity-10 align-middle border-start border-end">
                                            <h6 class="mb-0 fw-bold text-info-emphasis val-sem1"><?php echo $sem1; ?></h6>
                                        </td>

                                        <td class="text-center px-1">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <input type="number" name="notas[III Parcial][<?php echo $row['id_matricula']; ?>]"
                                                    class="form-control text-center fw-bold border-2 nota-input p3"
                                                    value="<?php echo htmlspecialchars($row['notas']['III Parcial'] ?? ''); ?>"
                                                    min="0" max="100" step="0.01" placeholder="-" <?php echo $parciales_status['III Parcial'] ? '' : 'readonly tabindex="-1" style="background-color: #e9ecef; pointer-events:none;"'; ?>>
                                                <span class="badge bg-secondary cualitativa-span shadow-sm" style="min-width: 35px; font-size: 0.75rem;">-</span>
                                            </div>
                                        </td>

                                        <td class="text-center px-1">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <input type="number" name="notas[IV Parcial][<?php echo $row['id_matricula']; ?>]"
                                                    class="form-control text-center fw-bold border-2 nota-input p4"
                                                    value="<?php echo htmlspecialchars($row['notas']['IV Parcial'] ?? ''); ?>"
                                                    min="0" max="100" step="0.01" placeholder="-" <?php echo $parciales_status['IV Parcial'] ? '' : 'readonly tabindex="-1" style="background-color: #e9ecef; pointer-events:none;"'; ?>>
                                                <span class="badge bg-secondary cualitativa-span shadow-sm" style="min-width: 35px; font-size: 0.75rem;">-</span>
                                            </div>
                                        </td>

                                        <td class="text-center px-2 bg-info bg-opacity-10 align-middle border-start border-end">
                                            <h6 class="mb-0 fw-bold text-info-emphasis val-sem2"><?php echo $sem2; ?></h6>
                                        </td>

                                        <td class="text-center px-2 bg-primary bg-opacity-10 align-middle border-start">
                                            <h5 class="mb-0 fw-black text-primary val-final"><?php echo $final; ?></h5>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($alumnos)): ?>
                    <div class="card-footer bg-white border-top p-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary fw-bold px-5 rounded-pill shadow-sm">
                            <i class="bi bi-save-fill me-2"></i> Guardar Calificaciones
                        </button>
                    </div>
                <?php endif; ?>
                </tbody>
                </table>
        </div>
        </form>
    </div>
</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let inputs = document.querySelectorAll('.nota-input');
    inputs.forEach(input => {
        // Ejecución al cargar
        calcularLetra(input, <?php echo $es_preescolar; ?>);
        
        // Ejecución en tiempo real al escribir o cambiar
        input.addEventListener('keyup', function() {
            calcularLetra(this, <?php echo $es_preescolar; ?>);
            recalcularFila(this);
        });
        input.addEventListener('change', function() {
            calcularLetra(this, <?php echo $es_preescolar; ?>);
            recalcularFila(this);
        });
    });
});

// Función nueva: Calcula Semestres y Promedio Final instantáneamente
function recalcularFila(inputElement) {
    let fila = inputElement.closest('tr');
    
    let p1 = parseFloat(fila.querySelector('.p1').value);
    let p2 = parseFloat(fila.querySelector('.p2').value);
    let p3 = parseFloat(fila.querySelector('.p3').value);
    let p4 = parseFloat(fila.querySelector('.p4').value);
    
    // Regla de redondeo matemático para los Semestres (MINED)
    let sem1 = (!isNaN(p1) && !isNaN(p2)) ? Math.round((p1 + p2) / 2) : '-';
    let sem2 = (!isNaN(p3) && !isNaN(p4)) ? Math.round((p3 + p4) / 2) : '-';
    
    // La Nota Final es el promedio de ambos semestres
    let final = (sem1 !== '-' && sem2 !== '-') ? Math.round((sem1 + sem2) / 2) : '-';
    
    fila.querySelector('.val-sem1').innerText = sem1;
    fila.querySelector('.val-sem2').innerText = sem2;
    fila.querySelector('.val-final').innerText = final;
}

// Función existente: Asigna la letra según la escala
function calcularLetra(inputElement, esPreescolar) {
    let nota = parseFloat(inputElement.value);
    let spanElement = inputElement.parentElement.querySelector('.cualitativa-span');
    
    if (isNaN(nota) || nota < 0 || nota > 100) {
        spanElement.innerText = "-";
        spanElement.className = "badge bg-secondary cualitativa-span shadow-sm";
        return;
    }
    
    let letra = ""; let colorClase = "";
    if (nota >= 90) { 
        letra = esPreescolar ? "AA": "AA"; colorClase = "badge bg-success cualitativa-span shadow-sm"; 
    } else if (nota >= 76) { 
        letra = esPreescolar ? "AS" : "AS"; colorClase = "badge bg-primary cualitativa-span shadow-sm"; 
    } else if (nota >= 60) { 
        letra = esPreescolar ? "EP" : "AF"; colorClase = "badge bg-warning text-dark cualitativa-span shadow-sm"; 
    } else { 
        letra = esPreescolar ? "AI" : "AI"; colorClase = "badge bg-danger cualitativa-span shadow-sm"; 
    }
    
    spanElement.innerText = letra; 
    spanElement.className = colorClase;
}
</script>