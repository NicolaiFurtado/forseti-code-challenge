<?php
/**
 * Created by PhpStorm.
 * User: Usuário
 * Date: 16/06/2021
 * Time: 09:29
 */

require "connection.php";

$sql = "TRUNCATE TABLE news";
$stmt = $pdo->prepare($sql);
$stmt->execute();

header('Location: index.php');