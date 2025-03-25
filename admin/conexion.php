<?php
$host = 'localhost'; 
$user = 'root'; 
$password = ''; 
$database = 'mango'; 

$conexion = mysqli_connect($host, $user, $password, $database);

if (!$conexion) {
    die("Connection failed: " . mysqli_connect_error());
}
?> 