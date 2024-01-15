<?php
// Информация для подключения к базе данных
$username = 'root';
$password = '';
$host = 'localhost';
$dbname = 'example';

try {
    // Создание объекта PDO для подключения к базе данных
    $database = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8;", $username, $password);
} catch (PDOException $err) {
    // В случае ошибки выводится сообщение и программа завершает выполнение
    die('Ошибка подключения к БД: ' . $err->getMessage());
}
