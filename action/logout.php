<?php
// Начало сессии
session_start();

// Удаление идентификатора пользователя из сессии
unset($_SESSION['user_id']);

// Уничтожение сессии
session_destroy();

// Перенаправление на главную страницу
header('location: /');

// Прекращение выполнения кода
exit;
