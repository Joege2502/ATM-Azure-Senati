<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Conexión - ATM Azure</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #0a0a0f; color: white; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; text-align: center; }
        .error-card { background: #1a1a2e; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 400px; border: 1px solid #e74c3c; }
        h1 { color: #e74c3c; margin-bottom: 10px; }
        p { color: #ccc; font-size: 0.9em; line-height: 1.6; }
        .btn-reintentar { display: inline-block; margin-top: 25px; padding: 12px 25px; background: #1abc9c; color: white; text-decoration: none; border-radius: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="error-card">
        <h1>⚠️ Ups...</h1>
        <p>Parece que el servicio de base de datos en Azure está tardando en responder o se encuentra en mantenimiento.</p>
        <p><strong>Causa probable:</strong> El servidor de la nube está "despertando" o hay un límite de conexiones temporales.</p>
        <a href="index.php" class="btn-reintentar">Reintentar conexión</a>
    </div>
</body>
</html>