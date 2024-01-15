<?php
// Начинаем сессию (если она еще не начата)
session_start();

// Объявляем глобальную переменную $database
global $database;

// Подключаем файл с настройками базы данных
require 'config/database.php';

// Подготавливаем SQL-запрос для выборки всех товаров из таблицы products
$products = $database->prepare('SELECT * FROM products');

// Выполняем SQL-запрос
$products->execute();

// Получаем результат запроса в виде ассоциативного массива
$products = $products->fetchAll(PDO::FETCH_ASSOC); // Fetch the results
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>main</title>
</head>
<body>
// Включение файла header.php
<?php include 'includes/header.php' ?>

<!--вывод всех товаров-->
<?php foreach ($products as $item): ?>
    <!-- Вывод названия товара -->
    <p><?= $item['title'] ?></p>

    <!-- Вывод цены товара -->
    <p><?= $item['price'] ?></p>

    <!-- Создание ссылки на страницу товара с передачей ID товара в URL -->
    <a href="item.php?id=<?= $item['id'] ?>">ссылка на <?= $item['title'] ?></a>
<?php endforeach; ?>

// Включение файла footer.php
<?php include 'includes/footer.php' ?>
</body>
</html>
