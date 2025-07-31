<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_producto"])) {
    $id = intval($_POST["id_producto"]);

    $stmt = $conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "✅ Producto eliminado correctamente";
    } else {
        echo "❌ Error al eliminar el producto";
    }

    $stmt->close();
}
?>
