<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $precio = floatval($_POST["precio"]);

    if ($nombre && $precio >= 0) {
        $stmt = $conexion->prepare("INSERT INTO productos (nombre, precio) VALUES (?, ?)");
        $stmt->bind_param("sd", $nombre, $precio);

        if ($stmt->execute()) {
            echo "✅ Producto agregado correctamente";
        } else {
            echo "❌ Error al agregar el producto";
        }

        $stmt->close();
    } else {
        echo "❌ Datos inválidos";
    }
}
?>
