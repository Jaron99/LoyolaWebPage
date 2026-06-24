<?php
// Silenciamos las alertas menores para que no corrompan el archivo PDF
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
session_start();

if (!isset($_SESSION['rol'])) {
    die("Acceso denegado.");
}

require_once __DIR__ . '/../libs/fpdf/fpdf.php';
require_once __DIR__ . '/../models/estudiantes.model.php';

function decodificar($texto)
{
    return mb_convert_encoding($texto ?? '', 'ISO-8859-1', 'UTF-8');
}

// =======================================================
// CLASE PLANTILLA
// =======================================================
class PDF_Colegio extends FPDF
{
    public $grado_seccion = '';

    function Header()
    {
        // Logo del Colegio
        if (file_exists(__DIR__ . '/../Imagenes/Logo.png')) {
            $this->Image(__DIR__ . '/../Imagenes/Logo.png', 10, 8, 25);
        }

        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 7, decodificar('COLEGIO PARROQUIAL SAN IGNACIO DE LOYOLA'), 0, 1, 'C');

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 7, decodificar('SISTEMA DE REGISTRO ACADÉMICO'), 0, 1, 'C');

        $estudiantesModel = new Estudiantes();
        $añolectivo = $estudiantesModel->ObtenerAnoLectivo();

        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(0, 7, decodificar('AÑO LECTIVO ' . $añolectivo), 0, 1, 'C');
        $this->Ln(5);

        if (!empty($this->grado_seccion)) {
            $this->SetFont('Arial', 'B', 12);
            $this->SetTextColor(20, 50, 100); // Color azul elegante
            $this->Cell(0, 6, decodificar($this->grado_seccion), 0, 1, 'C');
        }

    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, decodificar('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');

        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, decodificar('Fecha de Emisión: ' . date('d/m/Y')), 0, 1, 'R');
        $this->Ln(10);
    }
}

$tipo = $_GET['tipo'] ?? '';
$estudiantesModel = new Estudiantes();

// 1. REPORTE GENERAL DE ALUMNOS (Administrador)
if ($tipo === 'general' && $_SESSION['rol'] === 'admin') {

    $pdf = new PDF_Colegio();
    $pdf->AliasNbPages();
    $pdf->AddPage('P', 'Letter');

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(20, 50, 100);
    $pdf->Cell(0, 10, decodificar('REPORTE GENERAL DE ESTUDIANTES MATRICULADOS'), 0, 1, 'C');
    $pdf->Ln(5);
    
    // Configuración de columnas (Anchos ajustados para que todo quepa en la hoja)
    $pdf->SetFillColor(220, 230, 240);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);

    // Encabezados: Enumerador, Nombre, Código, Grado, Sección, Estado
    $pdf->Cell(10, 8, '#', 1, 0, 'C', true);
    $pdf->Cell(70, 8, decodificar('NOMBRE COMPLETO'), 1, 0, 'C', true);
    $pdf->Cell(30, 8, decodificar('CÓDIGO'), 1, 0, 'C', true);
    $pdf->Cell(30, 8, decodificar('GRADO'), 1, 0, 'C', true);
    $pdf->Cell(20, 8, decodificar('SEC.'), 1, 0, 'C', true);
    $pdf->Cell(30, 8, decodificar('ESTADO'), 1, 1, 'C', true);

    
    $listaAlumnos = $estudiantesModel->obtenerEstudiantesFiltrados();

    // ORDENAMIENTO PERSONALIZADO
    usort($listaAlumnos, function ($a, $b) {
        $gradoA = $a['nombre_grad'] ?? '';
        $gradoB = $b['nombre_grad'] ?? '';
        if ($gradoA !== $gradoB) return strcmp($gradoA, $gradoB);
        return strcmp($a['seccion'] ?? '', $b['seccion'] ?? '');
    });

    $listaAlumnos = $estudiantesModel->obtenerEstudiantesFiltrados();

    $pdf->SetFont('Arial', '', 8);
    $contador = 1;
    
    // Aquí tomamos los datos de la vista que ya trae el grado concatenado
    foreach ($listaAlumnos as $alumno) {
        $nombreCompleto = ($alumno['nombres'] ?? '') . ' ' . ($alumno['apellidos'] ?? '');
        
        // Usamos la columna que ya trae todo listo
        $infoGrado = $alumno['grado_asignado'] ?? 'N/A';
        
        $pdf->Cell(10, 7, $contador++, 1, 0, 'C');
        $pdf->Cell(70, 7, decodificar($nombreCompleto), 1, 0, 'L');
        $pdf->Cell(30, 7, decodificar($alumno['cod_mined'] ?? 'N/A'), 1, 0, 'C');
        
        // Como ahora grado y sección están en una sola columna, ocupamos más espacio 
        // para el grado asignado y eliminamos la columna de sección solita
        $pdf->Cell(50, 7, decodificar($infoGrado), 1, 0, 'C');
        
        $pdf->Cell(30, 7, decodificar('MATRICULADO'), 1, 1, 'C');
    }

    $pdf->Output('I', 'Reporte_General_Estudiantes.pdf');
    exit();
}

// REPORTE NOTAS POR SECCION (ADMIN)
if ($tipo === 'seccion' && isset($_GET['id_seccion'])) {
    $id_seccion = $_GET['id_seccion'];
    
    require_once __DIR__ . '/../models/conexion.model.php';
    $conexion = Conexion::connect();
    
    // 1. Buscamos el nombre del grado y sección para el subtítulo
    $stmt_sec = $conexion->prepare("SELECT CONCAT(nombre_grad, ' - SECCIÓN ', nombre_sec) AS seccion_nombre FROM vw_grados_secciones WHERE id_seccion = ?");
    $stmt_sec->bind_param("i", $id_seccion);
    $stmt_sec->execute();
    $res_sec = $stmt_sec->get_result();
    $sec_row = $res_sec->fetch_assoc();
    $nombre_reporte_seccion = $sec_row['seccion_nombre'] ?? 'SECCIÓN NO ENCONTRADA';

    // 2. Inicializamos el PDF
    $pdf = new PDF_Colegio();
    $pdf->AliasNbPages();
    $pdf->AddPage('P', 'Letter'); // Formato Vertical
    
    // TÍTULO PRINCIPAL del documento
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->SetTextColor(30, 30, 30);
    $pdf->Cell(0, 8, decodificar('REPORTE DE CALIFICACIONES POR SECCIÓN'), 0, 1, 'C');
    
    // UBICACIÓN SOLICITADA: Subtítulo dinámico justo debajo del título principal
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetTextColor(20, 50, 100); // Color azul elegante distintivo
    $pdf->Cell(0, 6, decodificar($nombre_reporte_seccion), 0, 1, 'C');
    $pdf->Ln(5); // Margen de separación antes de renderizar la información de los alumnos
    
    // 3. Consultamos las notas de los alumnos de esa sección
    $stmt = $conexion->prepare("SELECT * FROM vw_notas_por_seccion_dinamica WHERE id_seccion = ? ORDER BY nombre_completo, nombre_asig");
    $stmt->bind_param("i", $id_seccion);
    $stmt->execute();
    $res = $stmt->get_result();
    
    $ultimo_estudiante = '';
    
    while($row = $res->fetch_assoc()) {
        
        // Estructuración por bloques de estudiantes
        if ($row['nombre_completo'] !== $ultimo_estudiante) {
            $ultimo_estudiante = $row['nombre_completo'];
            
            $pdf->Ln(4); 
            
            // Fila Gris para el Alumno
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell(196, 6, decodificar(' ESTUDIANTE: ' . $row['nombre_completo'] . '   |   CÓDIGO: ' . ($row['cod_mined'] ?? 'N/A')), 0, 1, 'L', true);
            
            // Encabezados de las columnas de materias
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(225, 235, 245);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(116, 5, 'Asignatura', 1, 0, 'L', true);
            $pdf->Cell(15, 5, 'P1', 1, 0, 'C', true);
            $pdf->Cell(15, 5, 'P2', 1, 0, 'C', true);
            $pdf->Cell(15, 5, 'P3', 1, 0, 'C', true);
            $pdf->Cell(15, 5, 'P4', 1, 0, 'C', true);
            $pdf->Cell(20, 5, 'Prom', 1, 1, 'C', true);
        }
        
        // Renglones de Asignaturas
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(116, 5, decodificar($row['nombre_asig']), 1);
        $pdf->Cell(15, 5, $row['p1'] ?? '-', 1, 0, 'C');
        $pdf->Cell(15, 5, $row['p2'] ?? '-', 1, 0, 'C');
        $pdf->Cell(15, 5, $row['p3'] ?? '-', 1, 0, 'C');
        $pdf->Cell(15, 5, $row['p4'] ?? '-', 1, 0, 'C');
        $pdf->Cell(20, 5, $row['promedio'] ?? '-', 1, 1, 'C');
    }
    
    $pdf->Output('I', 'Reporte_Seccion_' . str_replace(' ', '_', $nombre_reporte_seccion) . '.pdf');
    exit();
}
// =======================================================
// REPORTE BOLETÍN INDIVIDUAL (ADMIN)
// =======================================================
if ($tipo === 'individual' && isset($_GET['codigo_mined'])) {
    $codigo_mined = trim($_GET['codigo_mined']);
    
    require_once __DIR__ . '/../models/conexion.model.php';
    $conexion = Conexion::connect();
    
    // Consultamos la vista dinámica filtrando por el código MINED ingresado
    $stmt = $conexion->prepare("SELECT * FROM vw_notas_por_seccion_dinamica WHERE cod_mined = ? ORDER BY nombre_asig");
    $stmt->bind_param("s", $codigo_mined);
    $stmt->execute();
    $res = $stmt->get_result();
    
    // Si no encontramos al estudiante, mostramos un error amigable
    if ($res->num_rows === 0) {
        die("<h3>Error: No se encontró ningún estudiante matriculado con el código MINED: " . htmlspecialchars($codigo_mined) . "</h3><p>Verifique el código e intente nuevamente.</p>");
    }
    
    // Extraemos la información del estudiante (del primer registro) y agrupamos sus notas
    $notas_alumno = [];
    $datos_estudiante = null;
    
    while ($row = $res->fetch_assoc()) {
        if (!$datos_estudiante) {
            $datos_estudiante = [
                'nombre_completo' => $row['nombre_completo'],
                'cod_mined' => $row['cod_mined'],
                'grado_asignado' => $row['grado_asignado']
            ];
        }
        $notas_alumno[] = $row;
    }
    
    // Generación del documento PDF
    $pdf = new PDF_Colegio();
    $pdf->AliasNbPages();
    $pdf->AddPage('P', 'Letter');
    
    // Título Principal
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor(30, 30, 30);
    $pdf->Cell(0, 10, decodificar('BOLETÍN OFICIAL DE CALIFICACIONES'), 0, 1, 'C');
    $pdf->Ln(5);
    
    // Cuadro Elegante de Datos del Estudiante
    $pdf->SetFillColor(245, 245, 245);
    $pdf->SetDrawColor(200, 200, 200);
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 7, decodificar(' Nombre del Alumno:'), 'LT', 0, 'L', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(156, 7, decodificar(' ' . $datos_estudiante['nombre_completo']), 'RT', 1, 'L');
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 7, decodificar(' Código MINED:'), 'L', 0, 'L', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(156, 7, decodificar(' ' . $datos_estudiante['cod_mined']), 'R', 1, 'L');
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 7, decodificar(' Grado y Sección:'), 'LB', 0, 'L', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(156, 7, decodificar(' ' . $datos_estudiante['grado_asignado']), 'RB', 1, 'L');
    
    $pdf->Ln(10);
    
    // Tabla de Notas - Diseño de Encabezados
    $pdf->SetDrawColor(0, 0, 0); 
    $pdf->SetFillColor(20, 50, 100); // Azul oscuro institucional
    $pdf->SetTextColor(255, 255, 255); // Texto blanco
    $pdf->SetFont('Arial', 'B', 9);
    
    $pdf->Cell(76, 8, decodificar('Asignatura'), 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'I Parcial', 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'II Parcial', 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'III Parcial', 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'IV Parcial', 1, 0, 'C', true);
    $pdf->Cell(40, 8, 'Promedio Final', 1, 1, 'C', true);
    
    // Tabla de Notas - Datos dinámicos
    $pdf->SetTextColor(30, 30, 30); 
    
    foreach ($notas_alumno as $n) {
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(76, 7, decodificar(' ' . $n['nombre_asig']), 1, 0, 'L');
        $pdf->Cell(20, 7, $n['p1'] ?? '-', 1, 0, 'C');
        $pdf->Cell(20, 7, $n['p2'] ?? '-', 1, 0, 'C');
        $pdf->Cell(20, 7, $n['p3'] ?? '-', 1, 0, 'C');
        $pdf->Cell(20, 7, $n['p4'] ?? '-', 1, 0, 'C');
        
        // Destacar el promedio final poniéndolo en Negrita
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 7, $n['promedio'] ?? '-', 1, 1, 'C');
    }
    
    // Bloque Inferior de Firmas Autorizadas
    $pdf->Ln(35); // Margen considerable para las firmas
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(80, 5, '_________________________________', 0, 0, 'C');
    $pdf->Cell(36, 5, '', 0, 0, 'C');
    $pdf->Cell(80, 5, '_________________________________', 0, 1, 'C');
    
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(80, 5, 'Firma Director(a)', 0, 0, 'C');
    $pdf->Cell(36, 5, '', 0, 0, 'C');
    $pdf->Cell(80, 5, decodificar('Firma Docente Guía'), 0, 1, 'C');
    
    // Finalizamos indicando el nombre del archivo al guardar
    $pdf->Output('I', 'Boletin_' . str_replace(' ', '_', $datos_estudiante['cod_mined']) . '.pdf');
    exit();
}

// =======================================================
// REPORTE DE CALIFICACIONES POR ASIGNATURA (DOCENTE)
// =======================================================
if ($tipo === 'seccion_materia' && isset($_GET['datos_asignatura'])) {
    
    // Separamos el id_seccion y el nombre de la asignatura que enviamos desde el select
    list($id_seccion, $nombre_asig) = explode('|', $_GET['datos_asignatura']);
    
    require_once __DIR__ . '/../models/conexion.model.php';
    $conexion = Conexion::connect();
    
    // 1. Buscamos el nombre del grado y sección para el subtítulo
    $stmt_sec = $conexion->prepare("SELECT CONCAT(nombre_grad, ' - SECCIÓN ', nombre_sec) AS seccion_nombre FROM vw_grados_secciones WHERE id_seccion = ?");
    $stmt_sec->bind_param("i", $id_seccion);
    $stmt_sec->execute();
    $res_sec = $stmt_sec->get_result();
    $sec_row = $res_sec->fetch_assoc();
    $nombre_reporte_seccion = $sec_row['seccion_nombre'] ?? 'SECCIÓN NO ENCONTRADA';

    // 2. Inicializamos el PDF en formato Vertical
    $pdf = new PDF_Colegio();
    $pdf->AliasNbPages();
    $pdf->AddPage('P', 'Letter'); 
    
    // Títulos
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->SetTextColor(30, 30, 30);
    $pdf->Cell(0, 8, decodificar('ACTA OFICIAL DE CALIFICACIONES'), 0, 1, 'C');
    
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetTextColor(20, 50, 100); 
    $pdf->Cell(0, 6, decodificar($nombre_reporte_seccion . ' | ' . $nombre_asig), 0, 1, 'C');
    $pdf->Ln(5); 
    
    // 3. Consultamos SOLO las notas de esa asignatura específica
    $stmt = $conexion->prepare("SELECT * FROM vw_notas_por_seccion_dinamica WHERE id_seccion = ? AND nombre_asig = ? ORDER BY nombre_completo");
    $stmt->bind_param("is", $id_seccion, $nombre_asig);
    $stmt->execute();
    $res = $stmt->get_result();
    
    // 4. Diseñamos la tabla
    $pdf->SetDrawColor(0, 0, 0); 
    $pdf->SetFillColor(111, 66, 193); // Morado institucional (combina con el módulo de docentes)
    $pdf->SetTextColor(255, 255, 255); 
    $pdf->SetFont('Arial', 'B', 9);
    
    // Ajuste de columnas para formato Vertical
    $pdf->Cell(10, 8, '#', 1, 0, 'C', true);
    $pdf->Cell(86, 8, decodificar('Nombre del Estudiante'), 1, 0, 'L', true);
    $pdf->Cell(15, 8, 'P1', 1, 0, 'C', true);
    $pdf->Cell(15, 8, 'P2', 1, 0, 'C', true);
    $pdf->Cell(15, 8, 'P3', 1, 0, 'C', true);
    $pdf->Cell(15, 8, 'P4', 1, 0, 'C', true);
    $pdf->Cell(40, 8, 'Promedio Final', 1, 1, 'C', true);
    
    // Filas de los alumnos
    $pdf->SetTextColor(30, 30, 30); 
    $pdf->SetFont('Arial', '', 9);
    
    $contador = 1;
    $hay_datos = false;
    
    while($row = $res->fetch_assoc()) {
        $hay_datos = true;
        
        // Alternar color de fondo para facilitar la lectura de la lista (efecto cebra)
        $fill = ($contador % 2 == 0) ? true : false;
        $pdf->SetFillColor(245, 245, 245);
        
        $pdf->Cell(10, 7, $contador++, 1, 0, 'C', $fill);
        $pdf->Cell(86, 7, decodificar(' ' . $row['nombre_completo']), 1, 0, 'L', $fill);
        $pdf->Cell(15, 7, $row['p1'] ?? '-', 1, 0, 'C', $fill);
        $pdf->Cell(15, 7, $row['p2'] ?? '-', 1, 0, 'C', $fill);
        $pdf->Cell(15, 7, $row['p3'] ?? '-', 1, 0, 'C', $fill);
        $pdf->Cell(15, 7, $row['p4'] ?? '-', 1, 0, 'C', $fill);
        
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 7, $row['promedio'] ?? '-', 1, 1, 'C', $fill);
        $pdf->SetFont('Arial', '', 9); // Regresar a fuente normal para la siguiente fila
    }
    
    if (!$hay_datos) {
        $pdf->Cell(196, 10, decodificar('No hay estudiantes matriculados en esta sección.'), 1, 1, 'C');
    }
    
    $pdf->Output('I', 'Acta_' . str_replace(' ', '_', $nombre_asig) . '.pdf');
    exit();
}

// =======================================================
// REPORTE MI BOLETÍN (VISTA DEL ESTUDIANTE)
// =======================================================
if ($tipo === 'mi_boletin' && $_SESSION['rol'] === 'estudiante') {
    
    // Obtenemos de forma segura el ID del alumno desde su inicio de sesión
    $id_alumno = $_SESSION['id_referencia'];
    
    require_once __DIR__ . '/../models/conexion.model.php';
    $conexion = Conexion::connect();
    
    // 1. Buscamos el código MINED del alumno basándonos en su ID
    $stmt_alum = $conexion->prepare("SELECT cod_mined FROM alumno WHERE id_alumno = ?");
    $stmt_alum->bind_param("i", $id_alumno);
    $stmt_alum->execute();
    $res_alum = $stmt_alum->get_result();
    
    if ($res_alum->num_rows === 0) {
        die("<h3>Error: No se encontró la información de su perfil.</h3>");
    }
    
    $alumno_data = $res_alum->fetch_assoc();
    $codigo_mined = $alumno_data['cod_mined'];

    // 2. Consultamos sus notas en la vista
    $stmt = $conexion->prepare("SELECT * FROM vw_notas_por_seccion_dinamica WHERE cod_mined = ? ORDER BY nombre_asig");
    $stmt->bind_param("s", $codigo_mined);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows === 0) {
        die("<h3>Error: Aún no tiene calificaciones registradas en el sistema en este año lectivo.</h3>");
    }
    
    // 3. Extraemos la información del estudiante y agrupamos sus notas
    $notas_alumno = [];
    $datos_estudiante = null;
    
    while ($row = $res->fetch_assoc()) {
        if (!$datos_estudiante) {
            $datos_estudiante = [
                'nombre_completo' => $row['nombre_completo'],
                'cod_mined' => $row['cod_mined'],
                'grado_asignado' => $row['grado_asignado']
            ];
        }
        $notas_alumno[] = $row;
    }
    
    // 4. Generación del documento PDF
    $pdf = new PDF_Colegio();
    $pdf->AliasNbPages();
    $pdf->AddPage('P', 'Letter');
    
    // Título Principal
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor(30, 30, 30);
    $pdf->Cell(0, 10, decodificar('MI BOLETÍN OFICIAL DE CALIFICACIONES'), 0, 1, 'C');
    $pdf->Ln(5);
    
    // Cuadro Elegante de Datos del Estudiante (Con bordes rojos sutiles)
    $pdf->SetFillColor(253, 245, 245); // Fondo casi blanco con un toque cálido
    $pdf->SetDrawColor(220, 180, 180);
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 7, decodificar(' Nombre del Alumno:'), 'LT', 0, 'L', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(156, 7, decodificar(' ' . $datos_estudiante['nombre_completo']), 'RT', 1, 'L');
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 7, decodificar(' Código MINED:'), 'L', 0, 'L', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(156, 7, decodificar(' ' . $datos_estudiante['cod_mined']), 'R', 1, 'L');
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 7, decodificar(' Grado y Sección:'), 'LB', 0, 'L', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(156, 7, decodificar(' ' . $datos_estudiante['grado_asignado']), 'RB', 1, 'L');
    
    $pdf->Ln(10);
    
    // Tabla de Notas - Diseño de Encabezados (Rojo Institucional)
    $pdf->SetDrawColor(0, 0, 0); 
    $pdf->SetFillColor(220, 53, 69); // Rojo tipo "danger" de Bootstrap
    $pdf->SetTextColor(255, 255, 255); 
    $pdf->SetFont('Arial', 'B', 9);
    
    $pdf->Cell(76, 8, decodificar('Asignatura'), 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'I Parcial', 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'II Parcial', 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'III Parcial', 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'IV Parcial', 1, 0, 'C', true);
    $pdf->Cell(40, 8, 'Promedio Final', 1, 1, 'C', true);
    
    // Tabla de Notas - Datos dinámicos
    $pdf->SetTextColor(30, 30, 30); 
    
    foreach ($notas_alumno as $n) {
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(76, 7, decodificar(' ' . $n['nombre_asig']), 1, 0, 'L');
        $pdf->Cell(20, 7, $n['p1'] ?? '-', 1, 0, 'C');
        $pdf->Cell(20, 7, $n['p2'] ?? '-', 1, 0, 'C');
        $pdf->Cell(20, 7, $n['p3'] ?? '-', 1, 0, 'C');
        $pdf->Cell(20, 7, $n['p4'] ?? '-', 1, 0, 'C');
        
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 7, $n['promedio'] ?? '-', 1, 1, 'C');
    }
    
    // Bloque Inferior de Firmas Autorizadas
    $pdf->Ln(35); 
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(80, 5, '_________________________________', 0, 0, 'C');
    $pdf->Cell(36, 5, '', 0, 0, 'C');
    $pdf->Cell(80, 5, '_________________________________', 0, 1, 'C');
    
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(80, 5, 'Firma Director(a)', 0, 0, 'C');
    $pdf->Cell(36, 5, '', 0, 0, 'C');
    $pdf->Cell(80, 5, decodificar('Firma Docente Guía'), 0, 1, 'C');
    
    $pdf->Output('I', 'Mi_Boletin_' . str_replace(' ', '_', $datos_estudiante['cod_mined']) . '.pdf');
    exit();
}


die("Tipo de reporte no válido o permisos insuficientes.");
