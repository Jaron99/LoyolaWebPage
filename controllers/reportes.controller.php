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

    function Header()
    {
        // Logo del Colegio
        if (file_exists(__DIR__ . '/../Imagenes/Logo.png')) {
            $this->Image(__DIR__ . '/../Imagenes/Logo.png', 10, 8, 25);
        }

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(30);
        $this->Cell(140, 8, decodificar('COLEGIO PARROQUIAL SAN IGNACIO DE LOYOLA'), 0, 1, 'C');

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30);
        $this->Cell(140, 5, decodificar('SISTEMA DE REGISTRO ACADÉMICO'), 0, 1, 'C');

        $estudiantesModel = new Estudiantes();
        $añolectivo = $estudiantesModel->ObtenerAnoLectivo();

        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(80, 80, 80); // Un gris un poco más claro para el año
        $this->Cell(0, 7, decodificar('AÑO LECTIVO ' . $añolectivo), 0, 1, 'C');
        $this->Ln(5); // Espacio antes de empezar la tabla

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

    // ... (encabezados de tabla iguales)
    
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

//REPORTE NOTAS POR SECCION (ADMIN)
if ($tipo === 'seccion' && isset($_GET['id_seccion'])) {
    $id_seccion = $_GET['id_seccion'];
    
    $pdf = new PDF_Colegio();
    $pdf->AddPage('L', 'Letter'); // Horizontal
    $pdf->SetFont('Arial', 'B', 10);
    
    // Títulos
    $pdf->Cell(0, 10, decodificar('REPORTE DE CALIFICACIONES POR SECCIÓN'), 0, 1, 'C');
    $pdf->Ln(5);
    
    // Encabezados de tabla
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(60, 8, 'Estudiante', 1, 0, 'C', true);
    $pdf->Cell(40, 8, 'Asignatura', 1, 0, 'C', true);
    $pdf->Cell(15, 8, 'P1', 1, 0, 'C', true);
    $pdf->Cell(15, 8, 'P2', 1, 0, 'C', true);
    $pdf->Cell(15, 8, 'P3', 1, 0, 'C', true);
    $pdf->Cell(15, 8, 'P4', 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'Prom', 1, 1, 'C', true);
    
    // Consulta a la vista
    $stmt = $this->conexion->prepare("SELECT * FROM vw_notas_por_seccion_dinamica WHERE id_seccion = ? ORDER BY nombre_completo");
    $stmt->bind_param("i", $id_seccion);
    $stmt->execute();
    $res = $stmt->get_result();
    
    $pdf->SetFont('Arial', '', 9);
    while($row = $res->fetch_assoc()) {
        $pdf->Cell(60, 7, decodificar($row['nombre_completo']), 1);
        $pdf->Cell(40, 7, decodificar($row['nombre_asig']), 1);
        $pdf->Cell(15, 7, $row['p1'] ?? '-', 1, 0, 'C');
        $pdf->Cell(15, 7, $row['p2'] ?? '-', 1, 0, 'C');
        $pdf->Cell(15, 7, $row['p3'] ?? '-', 1, 0, 'C');
        $pdf->Cell(15, 7, $row['p4'] ?? '-', 1, 0, 'C');
        $pdf->Cell(20, 7, $row['promedio'] ?? '-', 1, 1, 'C');
    }
    
    $pdf->Output('I', 'Reporte_Seccion.pdf');
    exit();
}


die("Tipo de reporte no válido o permisos insuficientes.");
