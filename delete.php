<?php
// Глобальная переменная для базы данных
global $database;

// Подключение файла с настройками базы данных
require 'config/database.php';

// Проверка, был ли передан параметр id через GET
if (isset($_GET['id'])) {
    // Подготовка запроса на выборку товара по id
    $product = $database->prepare('SELECT * FROM products WHERE id = :id');

    // Привязка значения id к параметру запроса
    $product->bindParam(':id', $_GET['id']);

    // Выполнение запроса
    $product->execute();

    // Получение результата запроса в виде ассоциативного массива
    $product = $product->fetch(PDO::FETCH_ASSOC);

    // Проверка существования товара с указанным id
    if (!$product) {
        // Если товар не найден, перенаправление на главную страницу
        header('location: ../index.php');
        exit;
    }
} else {
    // Если id не передан, перенаправление на главную страницу
    header('location: ../index.php');
    exit;
}

// Проверка, был ли отправлен запрос методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение id товара из параметра POST
    $product_id = $_GET['id'];

    // Подготовка запроса на удаление товара по id
    $stmt = $database->prepare("DELETE FROM products WHERE id = :product_id");

    // Привязка значения id к параметру запроса
    $stmt->bindParam(':product_id', $product_id);

    // Выполнение запроса
    if ($stmt->execute()) {
        // Если удаление прошло успешно, перенаправление на главную страницу
        header('location: ../index.php');
        exit();
    } else {
        // В случае ошибки в запросе
        $_SESSION['errors'][] = 'Ошибка при удалении товара';
    }
} else {
    // Если запрос не методом POST, добавление ошибки в массив ошибок
    $_SESSION['errors'][] = 'Неверный ID товара';
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $product['title'] ?></title>
</head>
<body>
<!-- Сообщение для пользователя -->
Вы уверены, что хотите удалить <?= $product['title'] ?>

<!-- Форма для подтверждения удаления -->
<form action="" method="post">
    <input type="submit" value="удалить">
    <a href="item.php?id=<?= $_GET['id'] ?>">назад</a>
</form>
</body>
</html>
