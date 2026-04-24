<?php
$serverName = "sdgtacna2005-database.database.windows.net";
$connectionOptions = array(
    "Database" => "tareaochodb",
    "Uid" => "adminJorge", 
    "PWD" => "12345678_x",
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    header("Location: error.php");
    exit();
}
?>