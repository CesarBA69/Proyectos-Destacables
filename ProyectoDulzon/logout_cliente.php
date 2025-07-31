<?php
session_start();
session_unset();
session_destroy();

// Eliminar cookie
if (isset($_COOKIE['usuario'])) {
    setcookie('usuario', '', time() - 3600, "/");
}

header("Location: login.html");
exit;
