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
    $password = saveInput($_POST['password']);
    if ($email && $password) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $database->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                if (password_verify($password, $result['password'])) {
                    if (count($_SESSION['errors']) === 0) {
                        $_SESSION['user_id'] = $result['id'];
                        header('location: ../index.php');
                        exit();
                    }
                } else {
                    $_SESSION['errors'][] = 'пароль не верен';
                }
            } else {
                $_SESSION['errors'][] = 'пользователь не зарегестрирован';
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
    <input type="text" name="email" placeholder="email">
    <input type="password" name="password" placeholder="password">
    <?php include 'functions/function.php' ?>
    <input type="submit" value="войти">
</form>
<a href="registration.php">нету аккаунта</a>