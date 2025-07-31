<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Empleado</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
      color: #2c3e50;
      background-color: #f4f6f7;
    }

    .reporte {
      border: 2px solid #34495e;
      border-radius: 10px;
      padding: 30px;
      max-width: 800px;
      background-color: #ffffff;
      margin: auto;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    h1 {
      color: #2c3e50;
      text-align: center;
      margin-bottom: 20px;
    }

    h2 {
      color: #34495e;
      border-bottom: 1px solid #ccc;
      padding-bottom: 5px;
      margin-top: 30px;
    }

    p {
      font-size: 16px;
      line-height: 1.6;
    }

    .pie {
      text-align: center;
      font-style: italic;
      color: #7f8c8d;
      margin-top: 50px;
    }
  </style>
</head>
<body>
  <div class="reporte">
    <h1>Reporte de Empleado</h1>

    <h2>Empleado seleccionado:</h2>
    <p><strong><?php echo $empleado; ?></strong></p>

    <h2>Contenido del reporte:</h2>
    <p><?php echo nl2br($contenido); ?></p>

    <div class="pie">
      Generado por el sistema Dulzón.
    </div>
  </div>
</body>
</html>
