<?php
global $database;
require 'config/database.php';

if (isset($_GET['id'])) {
    $product = $database->prepare('SELECT * FROM products WHERE id = :id');
    $product->bindParam(':id', $_GET['id']);
    $product->execute();
    $product = $product->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        header('location: /');
        exit;
    }
} else {
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
    <title><?= $product['title'] ?></title>
</head>
<body>
<p><?= $product['title'] ?></p>
<p><?= $product['price'] ?></p>
<a href="update.php?id=<?= $product['id'] ?>">редактировать <?= $product['title'] ?></a>
<br>
<a href="delete.php?id=<?= $product['id'] ?>">удалить <?= $product['title'] ?></a>
</body>
</html>
