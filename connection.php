<?php
/**
 * Created by PhpStorm.
 * User: Nicolai Furtado
 * Date: 16/06/2021
 * Time: 08:56
 */

$host = 'localhost';
$dbname = 'web-scrapping';
$user = 'root';
$pass = 'mysql';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conexão Efetuada";
    return $pdo;
}
catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}