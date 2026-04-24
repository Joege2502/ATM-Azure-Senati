<?php
$serverName = "sdgtacna2005-database.database.windows.net";
$connectionOptions = array(
    "Database" => "tareaochodb",
    "Uid" => "adminJorge", 
    "PWD" => "12345678_j",
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>