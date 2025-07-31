<?php
session_start();

function validaLogin($usuario, $contrasena) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=dulzon;charset=utf8', 'root', 'admin');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // buscamos en empleados
        $stmt = $pdo->prepare("SELECT id_empleado, contrasena, rol FROM empleados WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($empleado && md5($contrasena) === $empleado['contrasena']) {
            return ['tipo' => 'empleado', 'id' => $empleado['id_empleado'], 'rol' => $empleado['rol']];
        }

        // despues buscamos en clientes
        $stmt = $pdo->prepare("SELECT id_cliente, contrasena FROM clientes WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente && md5($contrasena) === $cliente['contrasena']) {
            return ['tipo' => 'cliente', 'id' => $cliente['id_cliente']];
        }

        return false;
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $recuerdame = isset($_POST['recuerdame']); 

    if (empty($usuario) || empty($contrasena)) {
        echo "Por favor ingresa usuario y contraseña.";
        exit;
    }

    $resultado = validaLogin($usuario, $contrasena);

    if ($resultado) {
        $_SESSION['usuario'] = $usuario;

        // cookie "Recuérdame"
        if ($recuerdame) {
            setcookie('usuario', $usuario, time() + (30 * 24 * 60 * 60), "/");
        } else {
            if (isset($_COOKIE['usuario'])) {
                setcookie('usuario', '', time() - 3600, "/");
            }
        }

        // esta parte nos ayuda a redireccionar al usuario a su tipo de pag. correspondiente
        if ($resultado['tipo'] === 'cliente') {
            $_SESSION['id_cliente'] = $resultado['id'];
            $_SESSION['rol'] = 'cliente';
            header("Location: pagina_cliente.php");
        } elseif ($resultado['tipo'] === 'empleado') {
            $_SESSION['id_empleado'] = $resultado['id'];
            $_SESSION['rol'] = $resultado['rol'];

            if ($resultado['rol'] === 'Admin') {
                header("Location: admin_inicio.php");
            } elseif ($resultado['rol'] === 'Atencion') {
                header("Location: atencion_inicio.php");
            } else {
                echo "Rol no válido.";
                exit;
            }
        }
        exit;
    } else {
        echo "Usuario o contraseña incorrectos. <a href='login.html'>Intentar de nuevo</a>";
    }
} else {
    header("Location: login.html");
    exit;
}
