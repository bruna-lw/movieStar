<?php

error_reporting(1);
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_USER_DEPRECATED);

$db_name = "moviestar";
$db_host = "localhost";
$db_user = "root";
$db_pass = "";

$conn = new PDO("mysql:dbname=" . $db_name . ";host=" . $db_host, $db_user, $db_pass);

// Habilitar erros PDO
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);