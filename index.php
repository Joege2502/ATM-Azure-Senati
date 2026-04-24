<?php
// 1. Configuración de la conexión a Azure SQL
$serverName = "sdgtacna2005-database.database.windows.net";
$connectionOptions = array(
    "Database" => "tareaochodb",
    "Uid" => "adminJorge", 
    "PWD" => "12345678_j", 
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);
$mensaje = "";
$acceso_concedido = false; // Variable para controlar qué pantalla mostrar

// Verificar si viene del redirect POST-Redirect-Get
if (isset($_GET['login']) && $_GET['login'] === 'success') {
    $acceso_concedido = true;
}

// 2. Lógica al presionar "Iniciar"
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_iniciar'])) {
    $usuario_ingresado = $_POST['usuario'];
    $pin_ingresado = $_POST['pin'];

    $tsql = "SELECT nombre_usuario FROM usuarios WHERE nombre_usuario = ? AND password_numerica = ?";
    $params = array($usuario_ingresado, $pin_ingresado);
    $getResults = sqlsrv_query($conn, $tsql, $params);

    if ($getResults === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
    
    if ($row) {
        $resultado_audit = "CORRECTO";
        // Registro de Auditoría
        $audit_user = $usuario_ingresado;
        $sql_audit = "INSERT INTO auditoria (usuario_intentado, resultado) VALUES (?, ?)";
        $params_audit = array($audit_user, $resultado_audit);
        sqlsrv_query($conn, $sql_audit, $params_audit);
        
        // Patrón Post-Redirect-Get: Redirigir después de procesar el POST
        header("Location: index.php?login=success");
        exit();
    } else {
        $resultado_audit = "ERROR";
        $mensaje = "<div style='color:#ff6b6b; font-weight:600; margin-bottom:10px;'>❌ PIN o Usuario Incorrecto.</div>";
        
        // Registro de Auditoría para intentos fallidos
        $audit_user = empty($usuario_ingresado) ? "desconocido" : $usuario_ingresado;
        $sql_audit = "INSERT INTO auditoria (usuario_intentado, resultado) VALUES (?, ?)";
        $params_audit = array($audit_user, $resultado_audit);
        sqlsrv_query($conn, $sql_audit, $params_audit);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM Login - Azure</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a0a0f 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Estilos del contenedor ATM */
        .atm-container {
            background: linear-gradient(145deg, #2d2e3f, #1a1a2e);
            border-radius: 20px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            color: white;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .screen {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
            color: #fff;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            font-size: 1.1em;
            letter-spacing: 0.5px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 0.9em;
            font-weight: 600;
            margin-bottom: 8px;
            color: #e0e0e0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px;
            margin: 0;
            border: none;
            border-radius: 10px;
            text-align: center;
            font-size: 1.1em;
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            background: white;
            box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.2);
            transform: scale(1.02);
        }

        .keypad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 25px 0;
        }

        button.key {
            padding: 18px;
            font-size: 1.3em;
            cursor: pointer;
            background: linear-gradient(145deg, #4a5568, #2d3748);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        button.key:hover {
            background: linear-gradient(145deg, #5a6b7a, #3d4758);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        button.key:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .btn-iniciar {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
            color: white;
            padding: 16px;
            border: none;
            cursor: pointer;
            font-weight: 700;
            width: 100%;
            margin-top: 15px;
            border-radius: 10px;
            font-size: 1.1em;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(26, 188, 156, 0.3);
        }

        .btn-iniciar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(26, 188, 156, 0.4);
        }

        .btn-iniciar:active {
            transform: translateY(0);
        }

        /* Estilos para el panel de auditoría */
        .audit-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            color: #333;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.6s ease-out;
        }

        .audit-container h2 {
            color: #1abc9c;
            margin-bottom: 10px;
            font-size: 1.8em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .audit-container h3 {
            color: #666;
            margin-bottom: 25px;
            font-weight: 500;
            font-size: 1.1em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        th {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
            color: white;
            padding: 16px;
            text-align: left;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.95em;
            letter-spacing: 1px;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background-color: #f8fafb;
            transform: scale(1.01);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr td:nth-child(2) {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.95em;
            letter-spacing: 0.5px;
        }

        .btn-volver {
            display: inline-block;
            margin-top: 30px;
            padding: 14px 30px;
            text-decoration: none;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-weight: 700;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.95em;
            letter-spacing: 1px;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-volver:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 600px) {
            .atm-container {
                max-width: 100%;
            }
            
            .audit-container {
                padding: 20px;
            }

            table {
                font-size: 0.9em;
            }

            th, td {
                padding: 12px 8px;
            }
        }
    </style>
</head>
<body>

<?php if (!$acceso_concedido): ?>
    <div class="atm-container">
        <div class="screen">
            LOGIN SENATI - AZURE <br>
            <span style="font-size: 0.85em; margin-top: 8px; display: block;">Sistema de Seguridad</span>
            <?php echo $mensaje; ?>
        </div>
        <form method="POST">
            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" name="usuario" id="usuario" required autocomplete="off">
            </div>

            <div class="form-group">
                <label>PIN de Acceso:</label>
                <input type="password" name="pin" id="pin" readonly placeholder="••••••">
            </div>

            <div class="keypad">
                <?php for($i=1; $i<=9; $i++): ?>
                    <button type="button" class="key" onclick="addNumber(<?php echo $i; ?>)"><?php echo $i; ?></button>
                <?php endfor; ?>
                <button type="button" class="key" onclick="addNumber(0)">0</button>
                <button type="button" class="key" onclick="clearPin()" style="grid-column: span 2; background: linear-gradient(145deg, #e74c3c, #c0392b);">Borrar</button>
            </div>
            <button type="submit" name="btn_iniciar" class="btn-iniciar">Iniciar Sesión</button>
        </form>
    </div>

<?php else: ?>
    <div class="audit-container">
        <h2>✔ Acceso Concedido</h2>
        <h3>Panel de Auditoría - Últimos Intentos de Acceso</h3>
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Resultado</th>
                    <th>Fecha y Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_list = "SELECT TOP 10 usuario_intentado, resultado, fecha_intento FROM auditoria ORDER BY fecha_intento DESC";
                $res_list = sqlsrv_query($conn, $sql_list);
                while ($row_audit = sqlsrv_fetch_array($res_list, SQLSRV_FETCH_ASSOC)) {
                    $color = ($row_audit['resultado'] == "CORRECTO") ? "#27ae60" : "#e74c3c";
                    echo "<tr>
                            <td>" . htmlspecialchars($row_audit['usuario_intentado']) . "</td>
                            <td style='color:$color;'>" . $row_audit['resultado'] . "</td>
                            <td>" . $row_audit['fecha_intento']->format('d/m/Y H:i:s') . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="index.php" class="btn-volver">← Cerrar Sesión</a>
    </div>
<?php endif; ?>

<script>
    function addNumber(num) {
        const pinField = document.getElementById('pin');
        if(pinField.value.length < 6) {
            pinField.value = pinField.value + num;
        }
    }

    function clearPin() {
        document.getElementById('pin').value = '';
    }
</script>

</body>
</html>