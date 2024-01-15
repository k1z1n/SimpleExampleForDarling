<?php
session_start();
global $database;
require 'config/database.php';
unset($_SESSION['errors']);
$_SESSION['errors'] = [];
function saveInput($input)
{
    $cleanedInput = trim($input);
    return htmlspecialchars($cleanedInput);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = saveInput($_POST['email']);
    $username = saveInput($_POST['username']);
    $password = saveInput($_POST['password']);
    $re_password = saveInput($_POST['re_password']);
    if ($email && $password) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $database->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (strlen($password) >= 8) {
                if ($password === $re_password) {
                    if (count($_SESSION['errors']) === 0) {
                        $hash_password = password_hash($password, PASSWORD_DEFAULT);
                        $query = $database->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
                        $query->bindParam(':email', $email);
                        $query->bindParam(':username', $username);
                        $query->bindParam(':password', $hash_password);
                        if ($query->execute()) {
                            header('location: login.php');
                            exit();
                        } else {
                            $_SESSION['errors'][] = 'ошибка регистрации';
                        }
                    }
                } else {
                    $_SESSION['errors'][] = 'парори не совпадают';
                }
            } else {
                $_SESSION['errors'][] = 'пароль должен содержать более 8 символов';
            }
        } else {
            $_SESSION['errors'][] = 'email не валиден';
        }
    } else {
        $_SESSION['errors'][] = 'заполните все поля';
    }
}
?>
<form action="" method="post">
    <input type="text" name="username" placeholder="имя">
    <input type="text" name="email" placeholder="email">
    <input type="password" name="password" placeholder="пароль">
    <input type="password" name="re_password" placeholder="повторить пароль">
    <?php include 'functions/function.php' ?>
    <input type="submit" value="регистрации">
</form>
