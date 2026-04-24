<?php
$serverName = "sdgtacna2005-database.database.windows.net"; // Tu servidor
$connectionOptions = array(
    "Database" => "tareaochodb", // Tu base de datos
    "Uid" => "adminJorge",     // El que creaste en Azure
    "PWD" => "12345678_j"     // Tu contraseña
);

// Estrecho de conexión para SQL Server en Azure
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    echo "Conexión exitosa";
}
if ($conn === false) {
    // Esto te mostrará el error real en la pantalla de la web
    echo "<pre>";
    die(print_r(sqlsrv_errors(), true));
    echo "</pre>";
}
?>