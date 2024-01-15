<?php
// Запуск сессии
session_start();

// Глобальная переменная для базы данных
global $database;

// Подключение файла с настройками базы данных
require 'config/database.php';

// Удаление предыдущих ошибок из сессии
unset($_SESSION['errors']);
$_SESSION['errors'] = [];

// Функция для очистки и защиты входных данных
function saveInput($input)
{
    $cleanedInput = trim($input);
    return htmlspecialchars($cleanedInput);
}

// Проверка, был ли отправлен запрос методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение и очистка данных из формы
    $title = saveInput($_POST['title']);
    $price = saveInput($_POST['price']);

    // Проверка наличия названия и цены
    if ($title && $price) {
        // Проверка наличия предыдущих ошибок
        if (count($_SESSION['errors']) === 0) {
            // Подготовка запроса на добавление нового товара в базу данных
            $query = $database->prepare("INSERT INTO products (title, price) VALUES (:title, :price)");

            // Привязка значений к параметрам запроса
            $query->bindParam(':title', $title);
            $query->bindParam(':price', $price);

            // Выполнение запроса
            if ($query->execute()) {
                // Перенаправление на главную страницу после успешного добавления
                header('location: index.php');
                exit();
            }
        }
    } else {
        // Добавление ошибки в сессию, если не заполнены все поля
        $_SESSION['errors'][] = 'Заполните все поля';
    }
}
?>
<!-- Форма для добавления нового товара -->
<form action="" method="post">
    <input type="text" name="title" placeholder="название">
    <input type="text" name="price" placeholder="цена">

    <!-- Вставка кода из функции, которая, видимо, отображает ошибки -->
    <?php include 'functions/function.php' ?>

    <input type="submit" value="add">
</form>
