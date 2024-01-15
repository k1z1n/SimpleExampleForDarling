<?php
// Начинаем сессию (если она еще не начата)
session_start();

// Глобальная переменная для базы данных
global $database;

// Подключение файла с настройками базы данных
require 'config/database.php';

// Очистка массива ошибок в сессии
unset($_SESSION['errors']);
$_SESSION['errors'] = [];

// Функция для очистки и безопасного сохранения пользовательского ввода
function saveInput($input)
{
    $cleanedInput = trim($input);
    return htmlspecialchars($cleanedInput);
}

// Проверка, был ли отправлен запрос методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение и очистка введенного email
    $email = saveInput($_POST['email']);

    // Получение и очистка введенного имени пользователя
    $username = saveInput($_POST['username']);

    // Получение и очистка введенного пароля
    $password = saveInput($_POST['password']);

    // Получение и очистка введенного повторного пароля
    $re_password = saveInput($_POST['re_password']);

    // Проверка наличия значения в обязательных полях email и password
    if ($email && $password) {
        // Проверка валидности email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Подготовка запроса на выборку пользователя по email
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $database->prepare($query);

            // Привязка значения email к параметру запроса
            $stmt->bindParam(':email', $email);

            // Выполнение запроса
            $stmt->execute();

            // Получение результата запроса в виде ассоциативного массива
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Проверка, существует ли пользователь с указанным email
            if (!$result) {
                // Проверка длины пароля
                if (strlen($password) >= 8) {
                    // Проверка совпадения паролей
                    if ($password === $re_password) {
                        // Очистка массива ошибок (перед следующей проверкой)
                        unset($_SESSION['errors']);

                        // Хеширование пароля
                        $hash_password = password_hash($password, PASSWORD_DEFAULT);

                        // Подготовка запроса на вставку нового пользователя в таблицу users
                        $query = $database->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");

                        // Привязка значений к параметрам запроса
                        $query->bindParam(':email', $email);
                        $query->bindParam(':username', $username);
                        $query->bindParam(':password', $hash_password);

                        // Выполнение запроса
                        if ($query->execute()) {
                            // Если регистрация прошла успешно, перенаправление на страницу входа
                            header('location: login.php');
                            exit();
                        } else {
                            // В случае ошибки в запросе
                            $_SESSION['errors'][] = 'ошибка регистрации';
                        }
                    } else {
                        $_SESSION['errors'][] = 'пароли не совпадают';
                    }
                } else {
                    $_SESSION['errors'][] = 'пароль должен содержать более 8 символов';
                }
            } else {
                $_SESSION['errors'][] = 'пользователь с таким email уже зарегистрирован';
            }
        } else {
            $_SESSION['errors'][] = 'невалидный email';
        }
    } else {
        $_SESSION['errors'][] = 'заполните все обязательные поля';
    }
}
?>

<!-- Форма для ввода данных при регистрации -->
<form action="" method="post">
    <input type="text" name="username" placeholder="имя">
    <input type="text" name="email" placeholder="email">
    <input type="password" name="password" placeholder="пароль">
    <input type="password" name="re_password" placeholder="повторить пароль">

    <!-- Включение внешнего файла с дополнительными функциями -->
    <?php include 'functions/function.php' ?>

    <!-- Кнопка для отправки формы -->
    <input type="submit" value="регистрации">
</form>
