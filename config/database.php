<?php
$username = 'root';
$password = '';
$host = 'localhost';
$dbname = 'example';

try {
    $database = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8;", $username, $password);
}catch (PDOException $err){
    die('ошибка подключения к БД' . $err->getMessage());
}