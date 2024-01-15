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

    // Получение и очистка введенного пароля
    $password = saveInput($_POST['password']);

    // Проверка наличия значения в обоих полях email и password
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
            if ($result) {
                // Проверка корректности введенного пароля
                if (password_verify($password, $result['password'])) {
                    // Если пароль верен, установка user_id в сессии и перенаправление на главную страницу
                    if (count($_SESSION['errors']) === 0) {
                        $_SESSION['user_id'] = $result['id'];
                        header('location: ../index.php');
                        exit();
                    }
                } else {
                    $_SESSION['errors'][] = 'пароль не верен';
                }
            } else {
                $_SESSION['errors'][] = 'пользователь не зарегистрирован';
            }
        } else {
            $_SESSION['errors'][] = 'невалидный email';
        }
    } else {
        $_SESSION['errors'][] = 'заполните все поля';
    }
}
?>

<!-- Форма для ввода email и пароля с кнопкой для отправки запроса -->
<form action="" method="post">
    <input type="text" name="email" placeholder="email">
    <input type="password" name="password" placeholder="password">

    <!-- Включение внешнего файла с дополнительными функциями -->
    <?php include 'functions/function.php' ?>

    <!-- Кнопка для отправки формы -->
    <input type="submit" value="войти">
</form>

<!-- Ссылка для перехода на страницу регистрации -->
<a href="registration.php">нету аккаунта</a>
