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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_GET['id'];
    $stmt = $database->prepare("DELETE FROM products WHERE id = :product_id");
    $stmt->bindParam(':product_id', $product_id);
    if ($stmt->execute()) {
        header('location: ../index.php');
        exit();
    } else {
        $_SESSION['errors'][] = 'Ошибка при удалении товара';
    }
} else {
    $_SESSION['errors'][] = 'Неверный ID товара';
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
вы уверены что хотите удалить <?= $product['title'] ?>
<form action="" method="post">
    <input type="hidden" name="product_id" value="<?= $_GET['id'] ?>">
    <input type="submit" value="удалить">
    <a href="item.php?id=<?= $_GET['id'] ?>">назад</a>
</form>
</body>
</html>
