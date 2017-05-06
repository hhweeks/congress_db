<?php
ini_set('display_errors', 1);

$host = 'localhost';
$user = 'root';
$password = 'vagrant';
$database = 'congress';

$db = new mysqli($host, $user, $password, $database);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>
