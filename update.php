<?php
global $database;
require 'config/database.php';

if (isset($_GET['id'])) {
    $product = $database->prepare('SELECT * FROM products WHERE id = :id');
    $product->bindParam(':id', $_GET['id']);
    $product->execute();
    $product = $product->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        header('location: ../index.php');
        exit;
    }
} else {
    header('location: ../index.php');
    exit;
}

function sanitizeInput($input)
{
    $cleanedInput = trim($input);
    return htmlspecialchars($cleanedInput);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $price = sanitizeInput($_POST['price']);
    if($title && $price){
        $stmt = $database->prepare("UPDATE products SET title = :title, price = :price WHERE id = :product_id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':product_id', $_GET['id']);
        if ($stmt->execute()) {
            header("location: item.php?id={$_GET['id']}");
            exit();
        } else {
            $_SESSION['errors'][] = 'Ошибка при обновлении товара';
        }
    }else{
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
<form action="" method="post">
    <input type="text" name="title" value="<?= $product['title'] ?>">
    <input type="text" name="price" value="<?= $product['price'] ?>">
    <?php include 'functions/function.php' ?>
    <input type="submit" value="редактировать">
</form>
</body>
</html>
