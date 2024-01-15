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

// Функция для очистки и безопасного сохранения пользовательского ввода
function sanitizeInput($input)
{
    $cleanedInput = trim($input);
    return htmlspecialchars($cleanedInput);
}

// Проверка, был ли отправлен запрос методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение и очистка введенного названия товара
    $title = sanitizeInput($_POST['title']);

    // Получение и очистка введенной цены товара
    $price = sanitizeInput($_POST['price']);

    // Проверка, были ли введены значения в обоих полях
    if ($title && $price) {
        // Подготовка запроса на обновление данных товара
        $stmt = $database->prepare("UPDATE products SET title = :title, price = :price WHERE id = :product_id");

        // Привязка значений к параметрам запроса
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':product_id', $_GET['id']);

        // Выполнение запроса
        if ($stmt->execute()) {
            // Если обновление прошло успешно, перенаправление на страницу товара
            header("location: item.php?id={$_GET['id']}");
            exit();
        } else {
            // В случае ошибки в запросе
            $_SESSION['errors'][] = 'Ошибка при обновлении товара';
        }
    } else {
        // Если не все поля заполнены, добавление ошибки в массив ошибок
        $_SESSION['errors'][] = 'Заполните все поля';
    }
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
<!-- Форма для редактирования товара с предзаполненными данными -->
<form action="" method="post">
    <input type="text" name="title" value="<?= $product['title'] ?>">
    <input type="text" name="price" value="<?= $product['price'] ?>">

    <!-- Включение внешнего файла с дополнительными функциями -->
    <?php include 'functions/function.php' ?>

    <!-- Кнопка для отправки формы -->
    <input type="submit" value="редактировать">
</form>
</body>
</html>
