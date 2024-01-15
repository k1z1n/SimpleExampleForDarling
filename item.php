<?php
// Глобальная переменная для базы данных
global $database;

// Подключение файла с настройками базы данных
require 'config/database.php';

// Проверка наличия параметра 'id' в URL
if (isset($_GET['id'])) {
    // Подготовка запроса на выборку товара по ID из таблицы products
    $product = $database->prepare('SELECT * FROM products WHERE id = :id');

    // Привязка значения параметра 'id' к параметру запроса
    $product->bindParam(':id', $_GET['id']);

    // Выполнение SQL-запроса
    $product->execute();

    // Получение результата запроса в виде ассоциативного массива
    $product = $product->fetch(PDO::FETCH_ASSOC);

    // Проверка, существует ли товар с указанным ID
    if (!$product) {
        // Если товар не найден, перенаправление на главную страницу
        header('location: /');
        exit;
    }
} else {
    // Если параметр 'id' отсутствует в URL, перенаправление на главную страницу
    header('location: /');
    exit;
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Заголовок страницы, который берется из названия товара -->
    <title><?= $product['title'] ?></title>
</head>
<body>
<!-- Вывод названия товара -->
<p><?= $product['title'] ?></p>

<!-- Вывод цены товара -->
<p><?= $product['price'] ?></p>

<!-- Создание ссылки для редактирования товара с передачей ID товара в URL -->
<a href="update.php?id=<?= $product['id'] ?>">редактировать <?= $product['title'] ?></a>
<br>

<!-- Создание ссылки для удаления товара с передачей ID товара в URL -->
<a href="delete.php?id=<?= $product['id'] ?>">удалить <?= $product['title'] ?></a>
</body>
</html>
