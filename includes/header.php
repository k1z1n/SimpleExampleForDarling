<div class="" style="display: flex; align-items: center; gap: 50px">
    <!-- Заголовок страницы -->
    <h1>тут должен быть хедер</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Если пользователь авторизован -->
        <a href="../action/logout.php">выход из аккаунта c id: <?= $_SESSION['user_id'] ?></a>
        <a href="../add.php">add</a>
    <?php else: ?>
        <!-- Если пользователь не авторизован -->
        <a href="../login.php">войти</a>
    <?php endif; ?>
</div>