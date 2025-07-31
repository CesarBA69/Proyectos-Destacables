<?php
require_once "Utils/html2pdf/Html2Pdf.php";

use Spipu\Html2Pdf\Html2Pdf;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empleado = htmlspecialchars($_POST['empleado']);
    $contenido = nl2br(htmlspecialchars($_POST['contenido'])); // conserva saltos de línea

    ob_start();
    include 'Plantillareporte.php'; // aquí se genera el contenido HTML
    $html = ob_get_clean();

    try {
        $pdf = new Html2Pdf('P', 'A4', 'es');
        $pdf->writeHTML($html);
        $pdf->output("Reporte_{$empleado}.pdf");
    } catch (Exception $e) {
        echo "Error generando PDF: " . $e->getMessage();
    }
} else {
    echo "Acceso no válido.";
}

