<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Ventas</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f6f7;
      color: #2e7d32;
    }

    .contenedor {
      width: 100%;
      padding: 40px 30px;
    }

    .reporte {
      border: 2px solid #388e3c;
      border-radius: 10px;
      padding: 30px;
      background-color: #ffffff;
      max-width: 750px;
      margin: auto;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    h1, h2, h3 {
      color: #2e7d32;
      margin-bottom: 10px;
    }

    h1 {
      text-align: center;
    }

    .detalle {
      margin-top: 30px;
      margin-bottom: 20px;
    }

    .detalle p {
      font-size: 16px;
      margin: 5px 0;
      word-wrap: break-word;
    }

    .total {
      font-size: 16px;
      font-weight: bold;
      margin-top: 10px;
      margin-bottom: 30px;
    }

    .pie {
      text-align: center;
      font-style: italic;
      color: #81c784;
      margin-top: 50px;
    }
  </style>
</head>
<body>
  <div class="contenedor">
    <div class="reporte">
      <h1>Reporte de Ventas</h1>

      <h2>Empleado:</h2>
      <p><strong><?= htmlspecialchars($nombre_empleado) ?></strong></p>

      <div class="detalle">
        <h3>Detalle de ventas:</h3>
        <?php foreach ($ventas as $venta): ?>
          <p><?= htmlspecialchars($venta['producto']) ?> x<?= $venta['cantidad'] ?> = $<?= number_format($venta['subtotal'], 2) ?></p>
        <?php endforeach; ?>
      </div>

      <p class="total">Total: $<?= number_format($total, 2) ?></p>

      <div class="pie">
        Generado por el sistema Dulzón.
      </div>
    </div>
  </div>
</body>
</html>
