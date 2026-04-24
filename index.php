<?php
// 1. Configuración de conexión
$serverName = "sdgtacna2005-database.database.windows.net";
$connectionOptions = array(
    "Database" => "tareaochodb",
    "Uid" => "adminJorge", 
    "PWD" => "12345678_j", 
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);
$mensaje = "";
$acceso_concedido = false; 
$nombre_usuario = "";

if (isset($_GET['login'])) {
    if ($_GET['login'] === 'success') {
        $acceso_concedido = true;
        $nombre_usuario = "adminJorge"; // En un sistema real vendría de una $_SESSION
    } elseif ($_GET['login'] === 'error') {
        $mensaje = "<div style='color:#ff6b6b; font-weight:600; font-size:0.8em; margin-top:5px;'>❌ Datos Incorrectos</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_iniciar'])) {
    $usuario_ingresado = $_POST['usuario'];
    $pin_ingresado = $_POST['pin'];

    $tsql = "SELECT nombre_usuario FROM usuarios WHERE nombre_usuario = ? AND password_numerica = ?";
    $params = array($usuario_ingresado, $pin_ingresado);
    $getResults = sqlsrv_query($conn, $tsql, $params);

    $row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
    
    if ($row) {
        sqlsrv_query($conn, "INSERT INTO auditoria (usuario_intentado, resultado) VALUES (?, 'CORRECTO')", array($usuario_ingresado));
        header("Location: index.php?login=success");
        exit();
    } else {
        $audit_user = empty($usuario_ingresado) ? "desconocido" : $usuario_ingresado;
        sqlsrv_query($conn, "INSERT INTO auditoria (usuario_intentado, resultado) VALUES (?, 'ERROR')", array($audit_user));
        header("Location: index.php?login=error");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM Premium - Azure</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a0a0f 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 15px;
        }

        .atm-container {
            background: linear-gradient(145deg, #2d2e3f, #1a1a2e);
            border-radius: 20px; padding: 25px; width: 100%; max-width: 380px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5); color: white;
        }

        /* Mejora Responsive: Pantallas pequeñas */
        @media (max-width: 400px) {
            .atm-container { padding: 15px; }
            button.key { padding: 12px !important; font-size: 1.1em !important; }
        }

        .screen {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
            color: #fff; padding: 15px; margin-bottom: 20px; border-radius: 12px;
            text-align: center; box-shadow: inset 0 2px 5px rgba(0,0,0,0.2);
            min-height: 90px; display: flex; flex-direction: column; justify-content: center;
        }

        input {
            width: 100%; padding: 12px; border-radius: 8px; border: none;
            text-align: center; font-size: 1.1em; background: rgba(255,255,255,0.9);
            margin-bottom: 15px; font-family: inherit;
        }

        .keypad { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        button.key {
            padding: 15px; font-size: 1.2em; cursor: pointer; color: white;
            background: #3d4758; border: 1px solid rgba(255,255,255,0.1); border-radius: 10px;
        }

        /* Spinner de carga */
        .spinner {
            display: none; width: 20px; height: 20px; border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%; border-top-color: #fff; animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .btn-iniciar {
            background: #1abc9c; color: white; padding: 15px; border: none;
            width: 100%; border-radius: 10px; font-weight: 700; cursor: pointer;
            display: flex; align-items: center; justify-content: center; margin-top: 15px;
        }

        /* Auditoría Estilizada */
        .audit-container {
            background: white; padding: 25px; border-radius: 20px; width: 100%; max-width: 800px;
            color: #333; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 0.9em; }
        th { background: #16a085; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        
        .welcome-msg { color: #16a085; font-weight: 700; font-size: 1.4em; margin-bottom: 5px; }
    </style>
</head>
<body>

<?php if (!$acceso_concedido): ?>
    <div class="atm-container">
        <div class="screen">
            <span style="font-size: 0.9em; opacity: 0.9;">CAJERO SENATI</span>
            <div id="status-text"><?php echo $mensaje; ?></div>
        </div>
        <form method="POST" id="loginForm" onsubmit="showLoading()">
            <label style="font-size: 0.7em; letter-spacing: 1px;">USUARIO</label>
            <input type="text" name="usuario" required autocomplete="off">
            
            <label style="font-size: 0.7em; letter-spacing: 1px;">PIN</label>
            <input type="password" name="pin" id="pin" readonly placeholder="••••••">
            
            <div class="keypad">
                <?php for($i=1; $i<=9; $i++): ?>
                    <button type="button" class="key" onclick="addNumber(<?php echo $i; ?>)"><?php echo $i; ?></button>
                <?php endfor; ?>
                <button type="button" class="key" onclick="addNumber(0)">0</button>
                <button type="button" class="key" onclick="clearPin()" style="grid-column: span 2; background: #e74c3c;">Borrar</button>
            </div>
            
            <button type="submit" name="btn_iniciar" id="submitBtn" class="btn-iniciar">
                <div class="spinner" id="loader"></div>
                <span id="btnText">INICIAR SESIÓN</span>
            </button>
        </form>
    </div>

<?php else: ?>
    <div class="audit-container">
        <div class="welcome-msg">¡Bienvenido, <?php echo $nombre_usuario; ?>!</div>
        <p style="color: #666; margin-bottom: 20px;">Has ingresado exitosamente al sistema.</p>
        
        <h4 style="border-bottom: 2px solid #16a085; padding-bottom: 5px;">Historial de Auditoría</h4>
        <div style="overflow-x: auto;"> <table>
                <thead>
                    <tr><th>Usuario</th><th>Estado</th><th>Fecha</th></tr>
                </thead>
                <tbody>
                    <?php
                    $res = sqlsrv_query($conn, "SELECT TOP 8 usuario_intentado, resultado, fecha_intento FROM auditoria ORDER BY fecha_intento DESC");
                    while ($r = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                        $c = ($r['resultado'] == "CORRECTO") ? "#27ae60" : "#e74c3c";
                        echo "<tr><td>".htmlspecialchars($r['usuario_intentado'])."</td><td style='color:$c; font-weight:bold;'>{$r['resultado']}</td><td>".$r['fecha_intento']->format('H:i:s d/m')."</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="index.php" style="display:block; margin-top:20px; color:#16a085; font-weight:700; text-decoration:none;">← SALIR</a>
    </div>
<?php endif; ?>

<script>
    function addNumber(n) {
        const p = document.getElementById('pin');
        if(p.value.length < 6) p.value += n;
    }
    function clearPin() { document.getElementById('pin').value = ''; }

    // Función para el feedback visual
    function showLoading() {
        document.getElementById('loader').style.display = 'block';
        document.getElementById('btnText').innerText = 'VERIFICANDO...';
        document.getElementById('submitBtn').style.opacity = '0.7';
        document.getElementById('submitBtn').disabled = true;
        return true; 
    }
</script>
</body>
</html>