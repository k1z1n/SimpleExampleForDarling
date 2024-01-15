<?php
session_start();
global $database;
require 'config/database.php';
$products = $database->prepare('SELECT * FROM products');
$products->execute();
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
<?php include 'includes/header.php' ?>
<!--вывод авторизованного id пользователя-->


<!--вывод всех товаров-->
<?php foreach ($products as $item): ?>
    <p><?= $item['title'] ?></p>
    <p><?= $item['price'] ?></p>
    <a href="item.php?id=<?= $item['id'] ?>">ссылка на <?= $item['title'] ?></a>
<?php endforeach; ?>
<?php include 'includes/footer.php' ?>
</body>
</html>
