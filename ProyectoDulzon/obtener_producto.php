<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $precio = $_POST['precio'];

    // Validar archivo
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreImagen = strtolower(str_replace(' ', '', $nombre)) . ".jpeg"; // nombre imagen = nombre producto sin espacios
        $rutaDestino = "img/" . $nombreImagen;
        $tipoArchivo = mime_content_type($_FILES['imagen']['tmp_name']);

        // Validar tipo
        if (in_array($tipoArchivo, ['image/jpeg', 'image/png'])) {
            // Mover imagen
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                // Guardar en base de datos
                $stmt = $conexion->prepare("INSERT INTO productos (nombre, precio) VALUES (?, ?)");
                $stmt->bind_param("sd", $nombre, $precio);
                if ($stmt->execute()) {
                    echo "Producto registrado correctamente.<br>";
                    echo "<a href='index.php'>Volver a inicio</a>";
                } else {
                    echo "Error al guardar en la base de datos.";
                }
                $stmt->close();
            } else {
                echo "Error al mover la imagen al directorio.";
            }
        } else {
            echo "Formato de imagen no permitido. Solo JPG o PNG.";
        }
    } else {
        echo "No se subió ninguna imagen.";
    }

    $conexion->close();
}
?>

