<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empleado = $_POST['id_empleado'];

    // Verifica que no esté vacío y sea numérico
    if (!empty($id_empleado) && is_numeric($id_empleado)) {
        $stmt = $conexion->prepare("DELETE FROM empleados WHERE id_empleado = ?");
        $stmt->bind_param("i", $id_empleado);

        if ($stmt->execute()) {
            echo "Empleado eliminado correctamente.";
        } else {
            echo "Error al eliminar el empleado.";
        }

        $stmt->close();
    } else {
        echo "ID inválido.";
    }

    $conexion->close();
}
?>
