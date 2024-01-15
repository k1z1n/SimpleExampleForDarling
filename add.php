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
    $title = saveInput($_POST['title']);
    $price = saveInput($_POST['price']);
    if ($title && $price) {
        if (count($_SESSION['errors']) === 0) {
            $query = $database->prepare("INSERT INTO products (title, price) VALUES (:title, :price)");
            $query->bindParam(':title', $title);
            $query->bindParam(':price', $price);
            if ($query->execute()) {
                header('location: index.php');
                exit();
            }
        }
    } else {
        $_SESSION['errors'][] = 'заполните все поля';
    }
}
?>
<form action="" method="post">
    <input type="text" name="title" placeholder="название">
    <input type="text" name="price" placeholder="цена">
    <?php include 'functions/function.php' ?>
    <input type="submit" value="add">
</form>
