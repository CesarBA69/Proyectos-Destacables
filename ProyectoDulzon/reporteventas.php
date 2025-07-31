<?php
require_once "Utils/html2pdf/Html2Pdf.php";
use Spipu\Html2Pdf\Html2Pdf;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y limpiar nombre del empleado
    $nombre_empleado = htmlspecialchars($_POST['nombre_empleado'] ?? 'Empleado');

    // Recibir y decodificar ventas JSON
    $ventas_json = $_POST['ventas'] ?? '[]';
    $ventas = json_decode($ventas_json, true);

    if (!is_array($ventas)) {
        echo "Error: El formato de las ventas no es válido.";
        exit;
    }

    // Calcular total y sanitizar datos de ventas
    $total = 0;
    foreach ($ventas as &$venta) {
        $venta['producto'] = htmlspecialchars($venta['producto']);
        $venta['cantidad'] = (int)$venta['cantidad'];
        $venta['subtotal'] = (float)$venta['subtotal'];
        $total += $venta['subtotal'];
    }

    // Generar el HTML desde la plantilla
    ob_start();
    include 'PlantillaRventas.php'; // Esta plantilla usa $nombre_empleado, $ventas y $total
    $html = ob_get_clean();

    try {
        $pdf = new Html2Pdf('P', 'A4', 'es');
        $pdf->setDefaultFont('Arial');
        $pdf->writeHTML($html);
        // Forzar descarga del PDF con nombre dinámico
        $pdf->output("reporteventas_{$nombre_empleado}.pdf");
    } catch (Exception $e) {
        echo "Error generando PDF: " . $e->getMessage();
    }
} else {
    echo "Acceso no válido.";
}
